<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\SchemeIdentifier;

interface BbanDetailsInterface extends SchemeIdentifierInterface
{
    /**
     * @return string
     */
    public function getBban(): string;
}
