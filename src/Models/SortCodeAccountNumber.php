<?php

declare(strict_types=1);

namespace TrueLayer\Models;

use TrueLayer\Constants\ExternalAccountTypes;

class SortCodeAccountNumber extends AbstractExternalAccountBeneficiary
{
    /**
     * @var string|null
     */
    private ?string $sortCode = null;

    /**
     * @var string|null
     */
    private ?string $accountNumber = null;

    /**
     * @return string|null
     */
    public function getSortCode(): ?string
    {
        return $this->sortCode;
    }

    /**
     * @param string $sortCode
     *
     * @return SortCodeAccountNumber
     */
    public function sortCode(string $sortCode): SortCodeAccountNumber
    {
        $this->sortCode = $sortCode;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAccountNumber(): ?string
    {
        return $this->accountNumber;
    }

    /**
     * @param string $accountNumber
     *
     * @return SortCodeAccountNumber
     */
    public function accountNumber(string $accountNumber): SortCodeAccountNumber
    {
        $this->accountNumber = $accountNumber;

        return $this;
    }

    /**
     * @return string
     */
    public function getSchemeType(): string
    {
        return ExternalAccountTypes::SORT_CODE_ACCOUNT_NUMBER;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->wrap([
            'type' => $this->getSchemeType(),
            'sort_code' => $this->getSortCode(),
            'account_number' => $this->getAccountNumber(),
        ]);
    }
}
