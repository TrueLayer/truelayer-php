<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\AccountIdentifier;

interface ScanDetailsInterface extends AccountIdentifierInterface
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
