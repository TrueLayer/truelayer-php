<?php

namespace TrueLayer\Services\Util;

use Illuminate\Encryption\Encrypter;
use Psr\SimpleCache\CacheInterface;
use TrueLayer\Constants\Encryption;
use TrueLayer\Contracts\EncryptedCacheInterface;
use TrueLayer\Exceptions\DecryptException;
use TrueLayer\Exceptions\EncryptException;
use TrueLayer\Exceptions\InvalidArgumentException;

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
     * @param string $key
     */
    public function __construct(CacheInterface $cache, string $key)
    {
        $this->cache = $cache;
        $this->encrypter = new Encrypter(hash('md5', $key), Encryption::ALGORITHM);
    }

    /**
     * @param string $key
     * @param null $default
     * @return mixed
     * @throws InvalidArgumentException
     * @throws DecryptException
     */
    public function get(string $key, $default = null)
    {
        $encryptedValue = $this->cache->get($key, $default);

        if (!is_string($encryptedValue)) {
            throw new InvalidArgumentException("The cached value must be string.");
        }

        try {
            return $this->encrypter->decrypt($encryptedValue);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            throw new DecryptException($e->getMessage(), $e->getCode(), $e->getPrevious());
        }
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param int|null $ttl
     * @return bool
     * @throws EncryptException
     * @throws InvalidArgumentException
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
     * @return bool
     * @throws InvalidArgumentException
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
