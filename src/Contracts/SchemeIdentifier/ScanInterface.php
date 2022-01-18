<?php

declare(strict_types=1);

namespace TrueLayer\Contracts\SchemeIdentifier;

interface ScanInterface extends ScanDetailsInterface
{
    /**
     * @param string $sortCode
     *
     * @return ScanInterface
     */
    public function sortCode(string $sortCode): ScanInterface;

    /**
     * @param string $accountNumber
     *
     * @return ScanInterface
     */
    public function accountNumber(string $accountNumber): ScanInterface;
}
