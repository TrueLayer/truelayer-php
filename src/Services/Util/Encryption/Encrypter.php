<?php

declare(strict_types=1);

namespace TrueLayer\Services\Util\Encryption;

use TrueLayer\Exceptions\DecryptException;
use TrueLayer\Exceptions\EncryptException;

class Encrypter
{
    /**
     * The encryption key.
     *
     * @var string
     */
    protected string $key;

    /**
     * The previous / legacy encryption keys.
     *
     * @var string[]
     */
    protected array $previousKeys = [];

    /**
     * The algorithm used for encryption.
     *
     * @var string
     */
    protected string $cipher;

    /**
     * The supported cipher algorithms and their properties.
     *
     * @var array<string, array{'size': 16|32, "aead": bool}>
     */
    private static array $supportedCiphers = [
        'aes-128-cbc' => ['size' => 16, 'aead' => false],
        'aes-256-cbc' => ['size' => 32, 'aead' => false],
        'aes-128-gcm' => ['size' => 16, 'aead' => true],
        'aes-256-gcm' => ['size' => 32, 'aead' => true],
    ];

    /**
     * Create a new encrypter instance.
     *
     * @param string $key
     * @param string $cipher
     *
     * @throws \RuntimeException
     *
     * @return void
     */
    public function __construct(string $key, string $cipher = 'aes-128-cbc')
    {
        $key = (string) $key;

        if (!static::supported($key, $cipher)) {
            $ciphers = \implode(', ', \array_keys(self::$supportedCiphers));

            throw new \RuntimeException("Unsupported cipher or incorrect key length. Supported ciphers are: {$ciphers}.");
        }

        $this->key = $key;
        $this->cipher = $cipher;
    }

    /**
     * Determine if the given key and cipher combination is valid.
     *
     * @param string $key
     * @param string $cipher
     *
     * @return bool
     */
    public static function supported(string $key, string $cipher): bool
    {
        if (!isset(self::$supportedCiphers[\strtolower($cipher)])) {
            return false;
        }

        return \mb_strlen($key, '8bit') === self::$supportedCiphers[\strtolower($cipher)]['size'];
    }

    /**
     * Create a new encryption key for the given cipher.
     *
     * @param string $cipher
     *
     * @throws \Exception
     *
     * @return string
     */
    public static function generateKey(string $cipher): string
    {
        return \random_bytes(self::$supportedCiphers[\strtolower($cipher)]['size'] ?? 32);
    }

    /**
     * Encrypt the given value.
     *
     * @param mixed $value
     *
     * @throws EncryptException
     *
     * @return string
     */
    public function encrypt(mixed $value): string
    {
        $iv = \random_bytes(\max(1, \openssl_cipher_iv_length(\strtolower($this->cipher))));

        $value = \openssl_encrypt(
            \serialize($value),
            \strtolower($this->cipher), $this->key, 0, $iv, $tag
        );

        if ($value === false) {
            throw new EncryptException('Could not encrypt the data.');
        }

        $iv = \base64_encode($iv);
        $tag = \base64_encode($tag ?? '');

        $mac = self::$supportedCiphers[\strtolower($this->cipher)]['aead']
            ? '' // For AEAD-algorithms, the tag / MAC is returned by openssl_encrypt...
            : $this->hash($iv, $value, $this->key);

        $json = \json_encode(\compact('iv', 'value', 'mac', 'tag'), JSON_UNESCAPED_SLASHES);

        if (\json_last_error() !== JSON_ERROR_NONE || $json === false) {
            throw new EncryptException('Could not encrypt the data.');
        }

        return \base64_encode($json);
    }

    /**
     * Decrypt the given value.
     *
     * @param string $payload
     * @param bool   $unserialize
     *
     * @throws DecryptException
     *
     * @return mixed
     */
    public function decrypt(string $payload, bool $unserialize = true): mixed
    {
        $payload = $this->getJsonPayload($payload);

        $iv = \base64_decode($payload['iv']);

        $this->ensureTagIsValid(
            $tag = empty($payload['tag']) ? null : \base64_decode($payload['tag'])
        );

        $decrypted = false;

        // Here we will decrypt the value. If we are able to successfully decrypt it
        // we will then unserialize it and return it out to the caller. If we are
        // unable to decrypt this value we will throw out an exception message.
        foreach ($this->getAllKeys() as $key) {
            $decrypted = \openssl_decrypt(
                $payload['value'], \strtolower($this->cipher), $key, 0, $iv, $tag ?? ''
            );

            if ($decrypted !== false) {
                break;
            }
        }

        if ($decrypted === false) {
            throw new DecryptException('Could not decrypt the data.');
        }

        return $unserialize ? \unserialize($decrypted) : $decrypted;
    }

    /**
     * Create a MAC for the given value.
     *
     * @param string $iv
     * @param mixed  $value
     * @param string $key
     *
     * @return string
     */
    protected function hash(string $iv, mixed $value, string $key): string
    {
        return \hash_hmac('sha256', $iv . $value, $key);
    }

    /**
     * Get the JSON array from the given payload.
     *
     * @param string $payload
     *
     * @throws DecryptException
     *
     * @return array{'iv':string, 'value':string, 'mac':string, 'tag':string}
     */
    protected function getJsonPayload(string $payload): array
    {
        if (!\is_string($payload)) {
            throw new DecryptException('The payload is invalid.');
        }

        $payload = \json_decode(\base64_decode($payload), true);

        // If the payload is not valid JSON or does not have the proper keys set we will
        // assume it is invalid and bail out of the routine since we will not be able
        // to decrypt the given value. We'll also check the MAC for this encryption.
        if (!$this->validPayload($payload) || !\is_array($payload)) {
            throw new DecryptException('The payload is invalid.');
        }

        if (!self::$supportedCiphers[\strtolower($this->cipher)]['aead'] && !$this->validMac($payload)) {
            throw new DecryptException('The MAC is invalid.');
        }

        // @phpstan-ignore-next-line
        return $payload;
    }

    /**
     * Verify that the encryption payload is valid.
     *
     * @param mixed $payload
     *
     * @return bool
     */
    protected function validPayload(mixed $payload): bool
    {
        if (!\is_array($payload)) {
            return false;
        }

        foreach (['iv', 'value', 'mac'] as $item) {
            if (!isset($payload[$item]) || !\is_string($payload[$item])) {
                return false;
            }
        }

        if (isset($payload['tag']) && !\is_string($payload['tag'])) {
            return false;
        }

        $decoded = \base64_decode($payload['iv'], true);
        if ($decoded === false) {
            return false;
        }

        return \strlen($decoded) === \openssl_cipher_iv_length(\strtolower($this->cipher));
    }

    /**
     * Determine if the MAC for the given payload is valid.
     *
     * @param mixed[] $payload
     *
     * @return bool
     */
    protected function validMac(array $payload): bool
    {
        if (!isset($payload['iv']) || !\is_string($payload['iv']) || !\is_string($payload['mac'])) {
            return false;
        }

        foreach ($this->getAllKeys() as $key) {
            $valid = \hash_equals(
                $this->hash($payload['iv'], $payload['value'], $key), $payload['mac']
            );

            if ($valid === true) {
                return true;
            }
        }

        return false;
    }

    /**
     * Ensure the given tag is a valid tag given the selected cipher.
     *
     * @param string|null $tag
     *
     * @throws DecryptException
     *
     * @return void
     */
    protected function ensureTagIsValid(?string $tag): void
    {
        if (self::$supportedCiphers[\strtolower($this->cipher)]['aead'] && ($tag === null || \strlen($tag) !== 16)) {
            throw new DecryptException('Could not decrypt the data.');
        }

        if (!self::$supportedCiphers[\strtolower($this->cipher)]['aead'] && \is_string($tag)) {
            throw new DecryptException('Unable to use tag because the cipher algorithm does not support AEAD.');
        }
    }

    /**
     * Get the encryption key that the encrypter is currently using.
     *
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * Get the current encryption key and all previous encryption keys.
     *
     * @return string[]
     */
    public function getAllKeys(): array
    {
        return [$this->key, ...$this->previousKeys];
    }

    /**
     * Get the previous encryption keys.
     *
     * @return string[]
     */
    public function getPreviousKeys(): array
    {
        return $this->previousKeys;
    }

    /**
     * Set the previous / legacy encryption keys that should be utilized if decryption fails.
     *
     * @param string[] $keys
     *
     * @return $this
     */
    public function previousKeys(array $keys): self
    {
        foreach ($keys as $key) {
            if (!static::supported($key, $this->cipher)) {
                $ciphers = \implode(', ', \array_keys(self::$supportedCiphers));

                throw new \RuntimeException("Unsupported cipher or incorrect key length. Supported ciphers are: {$ciphers}.");
            }
        }

        $this->previousKeys = $keys;

        return $this;
    }
}
