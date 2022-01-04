<?php

declare(strict_types=1);

namespace TrueLayer\Services\Sdk;

use Psr\Http\Client\ClientInterface;
use TrueLayer\Contracts\Sdk\SdkConfigInterface;
use TrueLayer\Contracts\Sdk\SdkFactoryInterface;
use TrueLayer\Contracts\Sdk\SdkInterface;
use TrueLayer\Exceptions\InvalidArgumentException;

final class SdkConfig implements SdkConfigInterface
{
    /**
     * @var SdkFactoryInterface
     */
    private SdkFactoryInterface $factory;

    /**
     * @param SdkFactoryInterface $factory
     */
    public function __construct(SdkFactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @var string|null
     */
    private ?string $clientId = null;

    /**
     * @var string|null
     */
    private ?string $clientSecret = null;

    /**
     * @var string|null
     */
    private ?string $keyId = null;

    /**
     * @var string|null
     */
    private ?string $pem = null;

    /**
     * @var string|null
     */
    private ?string $passphrase = null;

    /**
     * @var bool
     */
    private bool $useProduction = false;

    /**
     * @var ClientInterface|null
     */
    private ?ClientInterface $httpClient = null;

    /**
     * @return string|null
     */
    public function getClientId(): ?string
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
     * @return string|null
     */
    public function getClientSecret(): ?string
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
     * @return string|null
     */
    public function getKeyId(): ?string
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
     * @return string|null
     */
    public function getPem(): ?string
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
     * @throws InvalidArgumentException
     *
     * @return $this
     */
    public function pemFile(string $path): self
    {
        $pem = \file_get_contents($path);

        if (!\is_string($pem)) {
            throw new InvalidArgumentException('Unable to load the key from the file.');
        }

        return $this->pem($pem);
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
     * @return ClientInterface|null
     */
    public function getHttpClient(): ?ClientInterface
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
     * @throws \TrueLayer\Exceptions\ApiRequestJsonSerializationException
     * @throws \TrueLayer\Exceptions\ApiRequestValidationException
     * @throws \TrueLayer\Exceptions\ApiResponseUnsuccessfulException
     * @throws \TrueLayer\Exceptions\ApiResponseValidationException
     *
     * @return SdkInterface
     */
    public function create(): SdkInterface
    {
        return $this->factory->make($this);
    }
}
