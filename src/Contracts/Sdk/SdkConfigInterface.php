<?php

declare(strict_types=1);

namespace TrueLayer\Contracts\Sdk;

use Psr\Http\Client\ClientInterface;
use Psr\SimpleCache\CacheInterface;
use TrueLayer\Exceptions\InvalidArgumentException;

interface SdkConfigInterface
{
    /**
     * @return string
     */
    public function getClientId(): string;

    /**
     * @param string $clientId
     *
     * @return $this
     */
    public function clientId(string $clientId): self;

    /**
     * @return string
     */
    public function getClientSecret(): string;

    /**
     * @param string $clientSecret
     *
     * @return $this
     */
    public function clientSecret(string $clientSecret): self;

    /**
     * @return string
     */
    public function getKeyId(): string;

    /**
     * @param string $keyId
     *
     * @return $this
     */
    public function keyId(string $keyId): self;

    /**
     * @return string
     */
    public function getPem(): string;

    /**
     * @param string $pem
     *
     * @return $this
     */
    public function pem(string $pem): self;

    /**
     * @param string $pem64
     *
     * @return $this
     */
    public function pem64(string $pem64): self;

    /**
     * @param string $path
     *
     * @throws InvalidArgumentException
     *
     * @return $this
     */
    public function pemFile(string $path): self;

    /**
     * @param string $passphrase
     *
     * @return $this
     */
    public function passphrase(string $passphrase): self;

    /**
     * @return string|null
     */
    public function getPassphrase(): ?string;

    /**
     * @return bool
     */
    public function shouldUseProduction(): bool;

    /**
     * @param bool $useProduction
     *
     * @return $this
     */
    public function useProduction(bool $useProduction): self;

    /**
     * @return ClientInterface|null
     */
    public function getHttpClient(): ?ClientInterface;

    /**
     * @param ClientInterface $httpClient
     *
     * @return $this
     */
    public function httpClient(ClientInterface $httpClient): self;

    /**
     * @return SdkInterface
     */
    public function create(): SdkInterface;

    /**
     * @return CacheInterface|null
     */
    public function getCache(): ?CacheInterface;

    /**
     * @param CacheInterface $cache
     *
     * @return $this
     */
    public function cache(CacheInterface $cache): self;
}
