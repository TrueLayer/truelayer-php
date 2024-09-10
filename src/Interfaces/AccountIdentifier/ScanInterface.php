<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\AccountIdentifier;

interface ScanInterface extends ScanDetailsInterface
{
    /**
     * @param string $sortCode
     * 6 digit sort code (no spaces or dashes).
     * Pattern: ^[0-9]{6}$
     *
     * @return ScanInterface
     */
    public function sortCode(string $sortCode): ScanInterface;

    /**
     * @param string $accountNumber
     * 8 digit account number.
     * Pattern: ^[0-9]{8}$
     *
     * @return ScanInterface
     */
    public function accountNumber(string $accountNumber): ScanInterface;
}
