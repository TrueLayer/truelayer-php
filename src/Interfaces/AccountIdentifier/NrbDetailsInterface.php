<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\AccountIdentifier;

interface NrbDetailsInterface extends AccountIdentifierInterface
{
    /**
     * @return string
     */
    public function getNrb(): string;
}
