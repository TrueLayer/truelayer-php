<?php

declare(strict_types=1);

namespace TrueLayer\Contracts;

interface EncryptedCacheInterface
{
    /**
     * @param string $key
     * @param null   $default
     *
     * @return mixed
     */
    public function get(string $key, $default = null);

    /**
     * @param string   $key
     * @param mixed    $value
     * @param int|null $ttl
     *
     * @return mixed
     */
    public function set(string $key, $value, ?int $ttl = null);

    /**
     * @param string $key
     *
     * @return bool
     */
    public function delete(string $key): bool;

    /**
     * @param string $key
     *
     * @return bool
     */
    public function has(string $key): bool;
}
