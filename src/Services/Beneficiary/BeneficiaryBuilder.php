<?php

declare(strict_types=1);

namespace TrueLayer\Services\Beneficiary;

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
     * @return SortCodeAccountNumber
     */
    public function sortCodeAccountNumber(): SortCodeAccountNumber
    {
        return SortCodeAccountNumber::make($this->getSdk());
    }

    /**
     * @return IbanAccountBeneficiary
     */
    public function ibanAccount(): IbanAccountBeneficiary
    {
        return IbanAccountBeneficiary::make($this->getSdk());
    }

    /**
     * @return MerchantAccountBeneficiary
     */
    public function merchantAccount(): MerchantAccountBeneficiary
    {
        return MerchantAccountBeneficiary::make($this->getSdk());
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
        $type = $data['type'] ?? null;
        $schemeType = $data['scheme_identifier']['type'] ?? null;

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
