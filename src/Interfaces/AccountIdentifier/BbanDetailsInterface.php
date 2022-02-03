<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\AccountIdentifier;

interface BbanDetailsInterface extends AccountIdentifierInterface
{
    /**
     * @return string
     */
    public function getBban(): string;
}
