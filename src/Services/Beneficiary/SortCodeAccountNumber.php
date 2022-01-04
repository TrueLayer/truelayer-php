<?php

declare(strict_types=1);

namespace TrueLayer\Services\Beneficiary;

use TrueLayer\Constants\BeneficiaryTypes;
use TrueLayer\Constants\ExternalAccountTypes;
use TrueLayer\Validation\AllowedConstant;

final class SortCodeAccountNumber extends AbstractExternalAccountBeneficiary
{
    /**
     * @return string|null
     */
    public function getSortCode(): ?string
    {
        return $this->getNullableString('scheme_identifier.sort_code');
    }

    /**
     * @param string|null $sortCode
     *
     * @return SortCodeAccountNumber
     */
    public function sortCode(string $sortCode = null): SortCodeAccountNumber
    {
        return $this->set('scheme_identifier.sort_code', $sortCode);
    }

    /**
     * @return string|null
     */
    public function getAccountNumber(): ?string
    {
        return $this->getNullableString('scheme_identifier.account_number');
    }

    /**
     * @param string|null $accountNumber
     *
     * @return SortCodeAccountNumber
     */
    public function accountNumber(string $accountNumber = null): SortCodeAccountNumber
    {
        return $this->set('scheme_identifier.account_number', $accountNumber);
    }

    /**
     * @return string
     */
    public function getSchemeType(): string
    {
        return ExternalAccountTypes::SORT_CODE_ACCOUNT_NUMBER;
    }

    /**
     * @return mixed[]
     */
    protected function rules(): array
    {
        return array_merge(parent::rules(), [
            'scheme_identifier.sort_code' => 'required|numeric|digits:6',
            'scheme_identifier.account_number' => 'required|numeric|digits:8'
        ]);
    }
}
