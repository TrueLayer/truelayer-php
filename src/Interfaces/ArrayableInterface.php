<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces;

interface ArrayableInterface
{
    /**
     * Get the instance as an array.
     *
     * @return mixed[]
     */
    public function toArray(): array;
}
