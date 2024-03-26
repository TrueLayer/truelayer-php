<?php

declare(strict_types=1);

namespace TrueLayer\Services\Configuration;

use Psr\Http\Client\ClientInterface as HttpClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\SimpleCache\CacheInterface;
use TrueLayer\Constants\Encryption;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Interfaces\Configuration\ConfigInterface;
use TrueLayer\Interfaces\EncryptedCacheInterface;
use TrueLayer\Services\Util\EncryptedCache;
use TrueLayer\Services\Util\Encryption\Encrypter;

abstract class Config implements ConfigInterface
{
    /**
     * @var bool
     */
    private bool $useProduction = false;

    /**
     * @var HttpClientInterface|null
     */
    private ?HttpClientInterface $httpClient = null;

    /**
     * @var RequestFactoryInterface|null
     */
    private ?RequestFactoryInterface $httpRequestFactory = null;

    /**
     * @var EncryptedCacheInterface|null
     */
    private ?EncryptedCacheInterface $cache = null;

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
     * @return HttpClientInterface|null
     */
    public function getHttpClient(): ?HttpClientInterface
    {
        return $this->httpClient;
    }

    /**
     * @param HttpClientInterface $httpClient
     *
     * @return $this
     */
    public function httpClient(HttpClientInterface $httpClient): self
    {
        $this->httpClient = $httpClient;

        return $this;
    }

    /**
     * @param RequestFactoryInterface $httpRequestFactory
     *
     * @return $this
     */
    public function httpRequestFactory(RequestFactoryInterface $httpRequestFactory): self
    {
        $this->httpRequestFactory = $httpRequestFactory;

        return $this;
    }

    /**
     * @return ?RequestFactoryInterface
     */
    public function getHttpRequestFactory(): ?RequestFactoryInterface
    {
        return $this->httpRequestFactory;
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
     * @return self
     */
    public function cache(CacheInterface $cache, string $encryptionKey): self
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
}
