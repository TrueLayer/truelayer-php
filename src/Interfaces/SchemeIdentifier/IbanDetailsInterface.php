<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\SchemeIdentifier;

interface IbanDetailsInterface extends SchemeIdentifierInterface
{
    /**
     * @return string
     */
    public function getIban(): string;
}
