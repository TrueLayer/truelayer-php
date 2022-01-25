<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\SchemeIdentifier;

interface ScanDetailsInterface extends SchemeIdentifierInterface
{
    /**
     * @return string
     */
    public function getSortCode(): string;

    /**
     * @return string
     */
    public function getAccountNumber(): string;
}
