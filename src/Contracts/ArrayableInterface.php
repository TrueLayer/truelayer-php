<?php

declare(strict_types=1);

namespace TrueLayer\Contracts;

interface ArrayableInterface
{
    /**
     * @return mixed[]
     */
    public function toArray(): array;
}
