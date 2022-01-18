<?php

declare(strict_types=1);

namespace TrueLayer\Contracts\SchemeIdentifier;

interface BbanDetailsInterface extends SchemeIdentifierInterface
{
    /**
     * @return string
     */
    public function getBban(): string;
}
