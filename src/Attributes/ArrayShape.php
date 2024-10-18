<?php

declare(strict_types=1);

namespace TrueLayer\Attributes;

#[\Attribute]
class ArrayShape
{
    public function __construct(public string $type)
    {
    }
}
