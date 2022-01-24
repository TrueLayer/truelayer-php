<?php

declare(strict_types=1);

namespace TrueLayer\Entities\SchemeIdentifier;

use TrueLayer\Entities\Entity;
use TrueLayer\Interfaces\SchemeIdentifier\ScanInterface;

final class Scan extends Entity implements ScanInterface
{
    /**
     * @var string
     */
    protected string $sortCode;

    /**
     * @var string
     */
    protected string $accountNumber;

    /**
     * @var string[]
     */
    protected array $arrayFields = [
        'sort_code',
        'account_number',
    ];

    /**
     * @var string[]
     */
    protected array $rules = [
        'sort_code' => 'required|numeric|digits:6',
        'account_number' => 'required|numeric|digits:8',
    ];

    /**
     * @return string
     */
    public function getSortCode(): string
    {
        return $this->sortCode;
    }

    /**
     * @param string $sortCode
     *
     * @return ScanInterface
     */
    public function sortCode(string $sortCode): ScanInterface
    {
        $this->sortCode = $sortCode;

        return $this;
    }

    /**
     * @return string
     */
    public function getAccountNumber(): string
    {
        return $this->accountNumber;
    }

    /**
     * @param string $accountNumber
     *
     * @return ScanInterface
     */
    public function accountNumber(string $accountNumber): ScanInterface
    {
        $this->accountNumber = $accountNumber;

        return $this;
    }
}
