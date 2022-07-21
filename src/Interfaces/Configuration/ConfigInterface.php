<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Configuration;

use Psr\Http\Client\ClientInterface as HttpClientInterface;
use Psr\SimpleCache\CacheInterface;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Interfaces\EncryptedCacheInterface;

interface ConfigInterface
{
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
     * @return HttpClientInterface
     */
    public function getHttpClient(): HttpClientInterface;

    /**
     * @param HttpClientInterface $httpClient
     *
     * @return $this
     */
    public function httpClient(HttpClientInterface $httpClient): self;

    /**
     * @return EncryptedCacheInterface|null
     */
    public function getCache(): ?EncryptedCacheInterface;

    /**
     * @param CacheInterface $cache
     * @param string         $encryptionKey
     *
     * @throws InvalidArgumentException
     *
     * @return $this
     */
    public function cache(CacheInterface $cache, string $encryptionKey): self;
}
