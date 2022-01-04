<?php

declare(strict_types=1);

namespace TrueLayer\Contracts;

interface ArrayFillableInterface
{
    /**
     * @param mixed[] $data
     *
     * @return $this
     */
    public function fill(array $data): self;
}
