<?php

namespace TrueLayer\Contracts;

interface ArrayFactoryInterface
{
    /**
     * @param array $data
     *
     * @return static
     */
    public static function fromArray(array $data): self;
}
