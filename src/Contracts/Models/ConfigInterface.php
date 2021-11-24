<?php

namespace TrueLayer\Contracts\Models;

use Psr\Http\Client\ClientInterface;

interface ConfigInterface
{
    /**
     * @return string
     */
    public function getClientId(): string;

    /**
     * @param string $clientId
     * @return $this
     */
    public function clientId(string $clientId): self;

    /**
     * @return string
     */
    public function getClientSecret(): string;

    /**
     * @param string $clientSecret
     * @return $this
     */
    public function clientSecret(string $clientSecret): self;

    /**
     * @return string
     */
    public function getKeyId(): string;

    /**
     * @param string $keyId
     * @return $this
     */
    public function keyId(string $keyId): self;

    /**
     * @return string
     */
    public function getPem(): string;

    /**
     * @param string $pem
     * @return $this
     */
    public function pem(string $pem): self;

    /**
     * @return bool
     */
    public function shouldUseProduction(): bool;

    /**
     * @param bool $useProduction
     * @return $this
     */
    public function useProduction(bool $useProduction): self;

    /**
     * @return ClientInterface|null
     */
    public function getHttpClient(): ?ClientInterface;

    /**
     * @param ClientInterface $client
     * @return $this
     */
    public function httpClient(ClientInterface $client): self;
}
