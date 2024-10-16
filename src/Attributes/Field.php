<?php

declare(strict_types=1);

namespace TrueLayer\Attributes;

#[\Attribute]
class Field
{
    public function __construct(public ?string $name = null)
    {
    }
}
