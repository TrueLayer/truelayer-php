<?php

declare(strict_types=1);

namespace TrueLayer\Services\Client;

use TrueLayer\Constants\Scopes;
use TrueLayer\Exceptions\MissingHttpImplementationException;
use TrueLayer\Exceptions\SignerException;
use TrueLayer\Interfaces\Client\ClientFactoryInterface;
use TrueLayer\Interfaces\Client\ClientInterface;
use TrueLayer\Interfaces\Configuration\ClientConfigInterface;
use TrueLayer\Services\Configuration\Config;

class ClientConfig extends Config implements ClientConfigInterface
{
    /**
     * @var ClientFactoryInterface
     */
    private ClientFactoryInterface $factory;

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
     * @var string[]
     */
    private array $scopes = [Scopes::DEFAULT];

    /**
     * @param ClientFactoryInterface $factory
     */
    public function __construct(ClientFactoryInterface $factory)
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
     * @throws SignerException
     *
     * @return ClientConfigInterface
     */
    public function pemBase64(string $pemBase64): ClientConfigInterface
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
     * @param string ...$scopes
     *
     * @return $this
     */
    public function scopes(string ...$scopes): self
    {
        $this->scopes = $scopes;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getScopes(): array
    {
        return $this->scopes;
    }

    /**
     * @throws SignerException
     * @throws MissingHttpImplementationException
     *
     * @return ClientInterface
     */
    public function create(): ClientInterface
    {
        return $this->factory->make($this);
    }
}
