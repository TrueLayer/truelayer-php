<?php

declare(strict_types=1);

namespace TrueLayer\Models;

use Psr\Http\Client\ClientInterface;
use TrueLayer\Contracts\Models\ConfigInterface;

class Config implements ConfigInterface
{
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
     * @var bool
     */
    private bool $useProduction = false;

    /**
     * @var ClientInterface|null
     */
    private ?ClientInterface $httpClient = null;

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
     * @param ClientInterface $client
     *
     * @return $this
     */
    public function httpClient(ClientInterface $httpClient): self
    {
        $this->httpClient = $httpClient;
    }
}
