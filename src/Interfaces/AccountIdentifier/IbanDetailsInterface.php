<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\AccountIdentifier;

interface IbanDetailsInterface extends AccountIdentifierInterface
{
    /**
     * @return string
     */
    public function getIban(): string;
}
