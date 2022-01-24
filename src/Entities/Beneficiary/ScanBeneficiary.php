<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Beneficiary;

use TrueLayer\Constants\BeneficiaryTypes;
use TrueLayer\Constants\ExternalAccountTypes;
use TrueLayer\Entities\Entity;
use TrueLayer\Interfaces\Beneficiary\ScanBeneficiaryInterface;

final class ScanBeneficiary extends Entity implements ScanBeneficiaryInterface
{
    /**
     * @var string
     */
    protected string $name;

    /**
     * @var string
     */
    protected string $reference;

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
        'scheme_identifier.account_number' => 'account_number',
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
    public function getName(): ?string
    {
        return $this->name ?? null;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function name(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getReference(): ?string
    {
        return $this->reference ?? null;
    }

    /**
     * @param string $reference
     *
     * @return $this
     */
    public function reference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

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
     * @return ScanBeneficiaryInterface
     */
    public function sortCode(string $sortCode): ScanBeneficiaryInterface
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
     * @return ScanBeneficiaryInterface
     */
    public function accountNumber(string $accountNumber): ScanBeneficiaryInterface
    {
        $this->accountNumber = $accountNumber;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return BeneficiaryTypes::EXTERNAL_ACCOUNT;
    }

    /**
     * @return string
     */
    public function getSchemeType(): string
    {
        return ExternalAccountTypes::SORT_CODE_ACCOUNT_NUMBER;
    }
}
