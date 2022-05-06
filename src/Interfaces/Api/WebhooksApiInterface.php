<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Api;

interface WebhooksApiInterface
{
    public function jwks(): array;
}
