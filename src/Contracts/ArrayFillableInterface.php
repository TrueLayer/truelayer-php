<?php

namespace TrueLayer\Contracts;

interface ArrayFillableInterface
{
    /**
     * @param array $data
     * @return $this
     */
    public function fill(array $data): self;
}
