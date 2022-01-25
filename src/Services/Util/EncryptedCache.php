<?php

declare(strict_types=1);

namespace TrueLayer\Services\Util;

use Illuminate\Encryption\Encrypter;
use Psr\SimpleCache\CacheInterface;
use TrueLayer\Exceptions\DecryptException;
use TrueLayer\Exceptions\EncryptException;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Interfaces\EncryptedCacheInterface;

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
     * @throws InvalidArgumentException
     * @throws DecryptException
     *
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        $encryptedValue = $this->cache->get($key, $default);

        if (!\is_string($encryptedValue)) {
            throw new InvalidArgumentException('The cached value must be string.');
        }

        try {
            return $this->encrypter->decrypt($encryptedValue);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            throw new DecryptException($e->getMessage(), $e->getCode(), $e->getPrevious());
        }
    }

    /**
     * @param string   $key
     * @param mixed    $value
     * @param int|null $ttl
     *
     * @throws InvalidArgumentException
     * @throws EncryptException
     *
     * @return bool
     */
    public function set(string $key, $value, ?int $ttl = null): bool
    {
        try {
            $encryptedValue = $this->encrypter->encrypt($value);

            return $this->cache->set($key, $encryptedValue, $ttl);
        } catch (\Illuminate\Contracts\Encryption\EncryptException $e) {
            throw new EncryptException($e->getMessage(), $e->getCode(), $e->getPrevious());
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
        return $this->cache->delete($key);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function has($key): bool
    {
        return $this->cache->has($key);
    }
}
