<?php

declare(strict_types=1);

namespace TrueLayer\Contracts;

interface ArrayableInterface
{
    /**
     * @return array
     */
    public function toArray(): array;
}
