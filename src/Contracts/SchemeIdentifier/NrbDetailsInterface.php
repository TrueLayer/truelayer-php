<?php

declare(strict_types=1);

namespace TrueLayer\Contracts\SchemeIdentifier;

interface NrbDetailsInterface extends SchemeIdentifierInterface
{
    /**
     * @return string
     */
    public function getNrb(): string;
}
