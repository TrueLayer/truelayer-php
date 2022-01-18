<?php

declare(strict_types=1);

namespace TrueLayer\Contracts\SchemeIdentifier;

interface IbanDetailsInterface extends SchemeIdentifierInterface
{
    /**
     * @return string
     */
    public function getIban(): string;
}
