<?php

declare(strict_types=1);

namespace TrueLayer\Entities;

use TrueLayer\Interfaces\RequestOptionsInterface;

final class RequestOptions extends Entity implements RequestOptionsInterface
{
    /**
     * @var string
     */
    protected string $idempotencyKey;

    /**
     * @var string[]
     */
    protected array $arrayFields = [
        'idempotency_key',
    ];

    /**
     * @param string $idempotencyKey
     *
     * @return RequestOptionsInterface
     */
    public function idempotencyKey(string $idempotencyKey): RequestOptionsInterface
    {
        $this->idempotencyKey = $idempotencyKey;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getIdempotencyKey(): ?string
    {
        return $this->idempotencyKey ?? null;
    }
}
