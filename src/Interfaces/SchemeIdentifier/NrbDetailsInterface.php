<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\SchemeIdentifier;

interface NrbDetailsInterface extends SchemeIdentifierInterface
{
    /**
     * @return string
     */
    public function getNrb(): string;
}
