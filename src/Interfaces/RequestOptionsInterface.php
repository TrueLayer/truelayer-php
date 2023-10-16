<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces;

interface RequestOptionsInterface extends ArrayableInterface
{
    /**
     * @param string $idempotencyKey
     *
     * @return RequestOptionsInterface
     */
    public function idempotencyKey(string $idempotencyKey): RequestOptionsInterface;

    /**
     * @return string|null
     */
    public function getIdempotencyKey(): ?string;
}
