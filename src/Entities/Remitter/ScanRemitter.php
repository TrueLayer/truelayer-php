<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Remitter;

use TrueLayer\Constants\AccountIdentifierTypes;
use TrueLayer\Entities\Entity;
use TrueLayer\Interfaces\AccountIdentifier\ScanInterface;
use TrueLayer\Interfaces\Remitter\ScanRemitterInterface;

class ScanRemitter extends Entity implements ScanRemitterInterface
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
        'type',
        'sort_code',
        'account_number',
    ];

    /**
     * @return string
     */
    public function getType(): string
    {
        return AccountIdentifierTypes::SORT_CODE_ACCOUNT_NUMBER;
    }

    /**
     * @return string
     */
    public function getSortCode(): string
    {
        return $this->sortCode;
    }

    /**
     * @param string $sortCode
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
     * @return ScanInterface
     */
    public function accountNumber(string $accountNumber): ScanInterface
    {
        $this->accountNumber = $accountNumber;
        return $this;
    }
}
