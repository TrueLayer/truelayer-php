<?php

declare(strict_types=1);

namespace TrueLayer\Builders;

use TrueLayer\Contracts\Builders\BeneficiaryBuilderInterface;
use TrueLayer\Models\IbanAccountBeneficiary;
use TrueLayer\Models\MerchantAccountBeneficiary;
use TrueLayer\Models\SortCodeAccountNumber;

class BeneficiaryBuilder implements BeneficiaryBuilderInterface
{
    /**
     * @param array $data
     *
     * @return SortCodeAccountNumber
     */
    public function sortCodeAccountNumber(array $data = []): SortCodeAccountNumber
    {
        return SortCodeAccountNumber::fromArray($data);
    }

    /**
     * @param array $data
     *
     * @return IbanAccountBeneficiary
     */
    public function ibanAccount(array $data = []): IbanAccountBeneficiary
    {
        return IbanAccountBeneficiary::fromArray($data);
    }

    /**
     * @param array $data
     *
     * @return MerchantAccountBeneficiary
     */
    public function merchantAccount(array $data = []): MerchantAccountBeneficiary
    {
        return MerchantAccountBeneficiary::fromArray($data);
    }
}
