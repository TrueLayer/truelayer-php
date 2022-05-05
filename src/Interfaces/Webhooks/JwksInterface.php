<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Webhooks;

interface JwksInterface
{
    /**
     * @return array
     */
    public function getJsonKeys(): array;

    /**
     * @return int|null
     */
    public function getRetrievedAt(): ?int;

    /**
     * @return bool
     */
    public function isExpired(): bool;

    /**
     * @return JwksInterface
     */
    public function clear(): JwksInterface;
}
