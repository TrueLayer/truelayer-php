<?php

declare(strict_types=1);

namespace TrueLayer\Services\Beneficiary;

use TrueLayer\Constants\BeneficiaryTypes;
use TrueLayer\Constants\ExternalAccountTypes;
use TrueLayer\Contracts\Beneficiary\BeneficiaryInterface;
use TrueLayer\Contracts\Beneficiary\BeneficiaryBuilderInterface;
use TrueLayer\Services\Beneficiary\IbanAccountBeneficiary;
use TrueLayer\Services\Beneficiary\MerchantAccountBeneficiary;
use TrueLayer\Services\Beneficiary\SortCodeAccountNumber;
use TrueLayer\Traits\WithSdk;

class BeneficiaryBuilder implements BeneficiaryBuilderInterface
{
    use WithSdk;

    /**
     * @param array $data
     *
     * @return SortCodeAccountNumber
     */
    public function sortCodeAccountNumber(array $data = []): SortCodeAccountNumber
    {
        return SortCodeAccountNumber::make($this->getSdk())->fill($data);
    }

    /**
     * @param array $data
     *
     * @return IbanAccountBeneficiary
     */
    public function ibanAccount(array $data = []): IbanAccountBeneficiary
    {
        return IbanAccountBeneficiary::make($this->getSdk())->fill($data);
    }

    /**
     * @param array $data
     *
     * @return MerchantAccountBeneficiary
     */
    public function merchantAccount(array $data = []): MerchantAccountBeneficiary
    {
        return MerchantAccountBeneficiary::make($this->getSdk())->fill($data);
    }

    /**
     * @param array $data
     * @return BeneficiaryInterface
     */
    public function fromArray(array $data): BeneficiaryInterface
    {
        $type = $data['type'] ?? null;
        $schemeType = $data['scheme_identifier']['type'] ?? null;

        if ($type === BeneficiaryTypes::EXTERNAL_ACCOUNT) {
            if ($schemeType === ExternalAccountTypes::SORT_CODE_ACCOUNT_NUMBER) {
                return SortCodeAccountNumber::make($this->getSdk())->fill($data);
            }

            if ($schemeType === ExternalAccountTypes::IBAN) {
                return IbanAccountBeneficiary::make($this->getSdk())->fill($data);
            }
        }

        if ($type === BeneficiaryTypes::MERCHANT_ACCOUNT) {
            return MerchantAccountBeneficiary::make($this->getSdk())->fill($data);
        }
    }
}
