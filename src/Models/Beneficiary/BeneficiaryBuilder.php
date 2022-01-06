<?php

declare(strict_types=1);

namespace TrueLayer\Models\Beneficiary;

use Illuminate\Support\Arr;
use TrueLayer\Constants\BeneficiaryTypes;
use TrueLayer\Constants\ExternalAccountTypes;
use TrueLayer\Contracts\Beneficiary\BeneficiaryBuilderInterface;
use TrueLayer\Contracts\Beneficiary\BeneficiaryInterface;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Exceptions\ValidationException;
use TrueLayer\Traits\WithSdk;

final class BeneficiaryBuilder implements BeneficiaryBuilderInterface
{
    use WithSdk;

    /**
     * @return ScanBeneficiary
     */
    public function sortCodeAccountNumber(): ScanBeneficiary
    {
        return ScanBeneficiary::make($this->getSdk());
    }

    /**
     * @return IbanBeneficiary
     */
    public function ibanAccount(): IbanBeneficiary
    {
        return IbanBeneficiary::make($this->getSdk());
    }

    /**
     * @return MerchantBeneficiary
     */
    public function merchantAccount(): MerchantBeneficiary
    {
        return MerchantBeneficiary::make($this->getSdk());
    }

    /**
     * @param mixed[] $data
     *
     * @throws InvalidArgumentException
     * @throws ValidationException
     *
     * @return BeneficiaryInterface
     */
    public function fill(array $data): BeneficiaryInterface
    {
        $type = Arr::get($data, 'type');
        $schemeType = Arr::get($data, 'scheme_identifier.type');

        if ($type === BeneficiaryTypes::EXTERNAL_ACCOUNT) {
            if ($schemeType === ExternalAccountTypes::SORT_CODE_ACCOUNT_NUMBER) {
                return $this->sortCodeAccountNumber()->fill($data);
            }

            if ($schemeType === ExternalAccountTypes::IBAN) {
                return $this->ibanAccount()->fill($data);
            }
        }

        if ($type === BeneficiaryTypes::MERCHANT_ACCOUNT) {
            return $this->merchantAccount()->fill($data);
        }

        throw new InvalidArgumentException('Unknown beneficiary type');
    }
}
