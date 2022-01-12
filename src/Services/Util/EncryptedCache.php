<?php

declare(strict_types=1);

namespace TrueLayer\Services\Util;

use Illuminate\Encryption\Encrypter;
use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;
use TrueLayer\Constants\Encryption;
use TrueLayer\Contracts\EncryptedCacheInterface;
use TrueLayer\Exceptions\DecryptException;
use TrueLayer\Exceptions\EncryptException;

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
     * @param string         $key
     */
    public function __construct(CacheInterface $cache, string $key)
    {
        $this->cache = $cache;
        $this->encrypter = new Encrypter(\hash('md5', $key), Encryption::ALGORITHM);
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

        try {
            return $this->encrypter->decrypt($encryptedValue);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            throw new DecryptException($e->getMessage(), $e->getCode(), $e->getPrevious());
        }
    }

    /**
     * @param string $key
     * @param $value
     * @param null $ttl
     *
     * @throws InvalidArgumentException
     * @throws EncryptException
     *
     * @return bool
     */
    public function set(string $key, $value, $ttl = null): bool
    {
        try {
            $encryptedValue = $this->encrypter->encrypt($value);

            return $this->cache->set($key, $encryptedValue, $ttl);
        } catch (\Illuminate\Contracts\Encryption\EncryptException $e) {
            throw new EncryptException($e->getMessage(), $e->getCode(), $e->getPrevious());
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
