<?php

declare(strict_types=1);

namespace TrueLayer\Models;

use TrueLayer\Constants\ExternalAccountTypes;

class SortCodeAccountNumber extends AbstractExternalAccountBeneficiary
{
    /**
     * @var string
     */
    private string $sortCode;

    /**
     * @var string
     */
    private string $accountNumber;

    /**
     * @param string $sortCode
     * @return SortCodeAccountNumber
     */
    public function sortCode(string $sortCode): SortCodeAccountNumber
    {
        $this->sortCode = $sortCode;
        return $this;
    }

    /**
     * @param string $accountNumber
     * @return SortCodeAccountNumber
     */
    public function accountNumber(string $accountNumber): SortCodeAccountNumber
    {
        $this->accountNumber = $accountNumber;
        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->wrap([
            'type' => ExternalAccountTypes::SORT_CODE_ACCOUNT_NUMBER,
            'sort_code' => $this->sortCode,
            'account_number' => $this->accountNumber,
        ]);
    }
}
