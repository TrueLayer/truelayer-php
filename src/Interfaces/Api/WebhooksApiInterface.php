<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Api;

interface WebhooksApiInterface
{
    /**
     * @return mixed[]
     */
    public function jwks(): array;
}
