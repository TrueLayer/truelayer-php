<?php

declare(strict_types=1);

namespace TrueLayer\Models\Beneficiary;

use TrueLayer\Constants\ExternalAccountTypes;

final class ScanBeneficiary extends AbstractExternalAccountBeneficiary
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
        'name',
        'reference',
        'scheme_identifier.type' => 'scheme_type',
        'scheme_identifier.sort_code' => 'sort_code',
        'scheme_identifier.account_number' => 'account_number'
    ];

    /**
     * @var string[]
     */
    protected array $rules = [
        'name' => 'required|string',
        'reference' => 'required|string',
        'scheme_identifier.sort_code' => 'required|numeric|digits:6',
        'scheme_identifier.account_number' => 'required|numeric|digits:8',
    ];

    /**
     * @return string|null
     */
    public function getSortCode(): ?string
    {
        return $this->sortCode ?? null;
    }

    /**
     * @param string $sortCode
     *
     * @return ScanBeneficiary
     */
    public function sortCode(string $sortCode): ScanBeneficiary
    {
        $this->sortCode = $sortCode;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getAccountNumber(): ?string
    {
        return $this->accountNumber ?? null;
    }

    /**
     * @param string $accountNumber
     *
     * @return ScanBeneficiary
     */
    public function accountNumber(string $accountNumber): ScanBeneficiary
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
}
