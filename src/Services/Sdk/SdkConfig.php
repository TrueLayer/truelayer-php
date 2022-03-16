<?php

declare(strict_types=1);

namespace TrueLayer\Services\Sdk;

use Illuminate\Encryption\Encrypter;
use Psr\Http\Client\ClientInterface;
use Psr\SimpleCache\CacheInterface;
use TrueLayer\Constants\Encryption;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Exceptions\SignerException;
use TrueLayer\Interfaces\EncryptedCacheInterface;
use TrueLayer\Interfaces\Sdk\SdkConfigInterface;
use TrueLayer\Interfaces\Sdk\SdkFactoryInterface;
use TrueLayer\Interfaces\Sdk\SdkInterface;
use TrueLayer\Services\Util\EncryptedCache;

class SdkConfig implements SdkConfigInterface
{
    /**
     * @var SdkFactoryInterface
     */
    private SdkFactoryInterface $factory;

    /**
     * @var string
     */
    private string $clientId;

    /**
     * @var string
     */
    private string $clientSecret;

    /**
     * @var string
     */
    private string $keyId;

    /**
     * @var string
     */
    private string $pem;

    /**
     * @var string|null
     */
    private ?string $passphrase = null;

    /**
     * @var bool
     */
    private bool $useProduction = false;

    /**
     * @var ClientInterface
     */
    private ClientInterface $httpClient;

    /**
     * @var EncryptedCacheInterface|null
     */
    private ?EncryptedCacheInterface $cache = null;

    /**
     * @param SdkFactoryInterface $factory
     */
    public function __construct(SdkFactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @return string
     */
    public function getClientId(): string
    {
        return $this->clientId;
    }

    /**
     * @param string $clientId
     *
     * @return $this
     */
    public function clientId(string $clientId): self
    {
        $this->clientId = $clientId;

        return $this;
    }

    /**
     * @return string
     */
    public function getClientSecret(): string
    {
        return $this->clientSecret;
    }

    /**
     * @param string $clientSecret
     *
     * @return $this
     */
    public function clientSecret(string $clientSecret): self
    {
        $this->clientSecret = $clientSecret;

        return $this;
    }

    /**
     * @return string
     */
    public function getKeyId(): string
    {
        return $this->keyId;
    }

    /**
     * @param string $keyId
     *
     * @return $this
     */
    public function keyId(string $keyId): self
    {
        $this->keyId = $keyId;

        return $this;
    }

    /**
     * @return string
     */
    public function getPem(): string
    {
        return $this->pem;
    }

    /**
     * @param string $pem
     *
     * @return $this
     */
    public function pem(string $pem): self
    {
        $this->pem = $pem;

        return $this;
    }

    /**
     * @param string $path
     *
     * @throws SignerException
     *
     * @return $this
     */
    public function pemFile(string $path): self
    {
        $pem = \file_get_contents($path);

        if (!\is_string($pem)) {
            throw new SignerException('Unable to load the key from the file.');
        }

        return $this->pem($pem);
    }

    /**
     * @param string $pemBase64
     *
     *@throws SignerException
     *
     * @return SdkConfigInterface
     */
    public function pemBase64(string $pemBase64): SdkConfigInterface
    {
        $decoded = \base64_decode($pemBase64);

        if ($decoded == false) {
            throw new SignerException('Could not decode base64 pem');
        }

        return $this->pem($decoded);
    }

    /**
     * @param string $passphrase
     *
     * @return $this
     */
    public function passphrase(string $passphrase): self
    {
        $this->passphrase = $passphrase;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPassphrase(): ?string
    {
        return $this->passphrase;
    }

    /**
     * @return bool
     */
    public function shouldUseProduction(): bool
    {
        return $this->useProduction;
    }

    /**
     * @param bool $useProduction
     *
     * @return $this
     */
    public function useProduction(bool $useProduction): self
    {
        $this->useProduction = $useProduction;

        return $this;
    }

    /**
     * @return ClientInterface
     */
    public function getHttpClient(): ClientInterface
    {
        return $this->httpClient;
    }

    /**
     * @param ClientInterface $httpClient
     *
     * @return $this
     */
    public function httpClient(ClientInterface $httpClient): self
    {
        $this->httpClient = $httpClient;

        return $this;
    }

    /**
     * @return EncryptedCacheInterface|null
     */
    public function getCache(): ?EncryptedCacheInterface
    {
        return $this->cache;
    }

    /**
     * @param CacheInterface $cache
     * @param string         $encryptionKey
     *
     * @throws InvalidArgumentException
     *
     * @return SdkConfigInterface
     */
    public function cache(CacheInterface $cache, string $encryptionKey): SdkConfigInterface
    {
        // TODO validate key length
        $binEncryptionKey = \hex2bin($encryptionKey);
        if (!$binEncryptionKey) {
            throw new InvalidArgumentException('Invalid encryption key. Please use `openssl rand -hex 32` to generate a valid one.');
        }

        $encrypter = new Encrypter($binEncryptionKey, Encryption::ALGORITHM);
        $this->cache = new EncryptedCache($cache, $encrypter);

        return $this;
    }

    /**
     *@throws SignerException
     *
     * @return SdkInterface
     */
    public function create(): SdkInterface
    {
        return $this->factory->make($this);
    }
}
