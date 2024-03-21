<?php

declare(strict_types=1);

namespace TrueLayer\Entities\AccountIdentifier;

use TrueLayer\Constants\AccountIdentifierTypes;
use TrueLayer\Entities\Entity;
use TrueLayer\Interfaces\AccountIdentifier\ScanInterface;

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
        'type',
        'sort_code',
        'account_number',
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

    /**
     * @return string
     */
    public function getType(): string
    {
        return AccountIdentifierTypes::SORT_CODE_ACCOUNT_NUMBER;
    }
}
