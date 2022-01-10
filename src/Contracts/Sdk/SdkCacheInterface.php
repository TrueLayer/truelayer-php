<?php

declare(strict_types=1);

namespace TrueLayer\Contracts\Sdk;

interface SdkCacheInterface
{
    /**
     * @param string $key
     *
     * @return string|null
     */
    public function get(string $key): ?string;

    /**
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool;

    /**
     * @param string $key
     * @param string $value
     * @param ?int $ttl
     *
     * @return $this
     */
    public function put(string $key, string $value, ?int $ttl = null): self;
}
