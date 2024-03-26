<?php

declare(strict_types=1);

namespace TrueLayer\Services\Util;

use Psr\SimpleCache\CacheInterface;
use TrueLayer\Exceptions\DecryptException;
use TrueLayer\Exceptions\EncryptException;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Interfaces\EncryptedCacheInterface;
use TrueLayer\Services\Util\Encryption\Encrypter;

final class EncryptedCache implements EncryptedCacheInterface
{
    /**
     * @var CacheInterface
     */
    private CacheInterface $cache;

    /**
     * @var Encrypter
     */
    private Encrypter $encrypter;

    /**
     * @param CacheInterface $cache
     * @param Encrypter      $encrypter
     */
    public function __construct(CacheInterface $cache, Encrypter $encrypter)
    {
        $this->cache = $cache;
        $this->encrypter = $encrypter;
    }

    /**
     * @param string $key
     * @param null   $default
     *
     * @throws DecryptException|InvalidArgumentException
     *
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        try {
            $encryptedValue = $this->cache->get($key, $default);
        } catch (\Psr\SimpleCache\InvalidArgumentException $e) {
            throw new InvalidArgumentException($e->getMessage(), $e->getCode(), $e->getPrevious());
        }

        if (!\is_string($encryptedValue)) {
            throw new InvalidArgumentException('The cached value must be string.');
        }

        return $this->encrypter->decrypt($encryptedValue);
    }

    /**
     * @param string   $key
     * @param mixed    $value
     * @param int|null $ttl
     *
     * @throws EncryptException
     * @throws InvalidArgumentException
     *
     * @return bool
     */
    public function set(string $key, $value, ?int $ttl = null): bool
    {
        try {
            $encryptedValue = $this->encrypter->encrypt($value);

            return $this->cache->set($key, $encryptedValue, $ttl);
        } catch (\Psr\SimpleCache\InvalidArgumentException $e) {
            throw new InvalidArgumentException($e->getMessage(), $e->getCode(), $e->getPrevious());
        }
    }

    /**
     * @param string $key
     *
     * @throws InvalidArgumentException
     *
     * @return bool
     */
    public function delete(string $key): bool
    {
        try {
            return $this->cache->delete($key);
        } catch (\Psr\SimpleCache\InvalidArgumentException $e) {
            throw new InvalidArgumentException($e->getMessage(), $e->getCode(), $e->getPrevious());
        }
    }

    /**
     * @throws InvalidArgumentException
     */
    public function has($key): bool
    {
        try {
            return $this->cache->has($key);
        } catch (\Psr\SimpleCache\InvalidArgumentException $e) {
            throw new InvalidArgumentException($e->getMessage(), $e->getCode(), $e->getPrevious());
        }
    }
}
