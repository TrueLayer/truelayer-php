<?php

declare(strict_types=1);

namespace TrueLayer\Builders;

use TrueLayer\Contracts\Builders\BeneficiaryBuilderInterface;
use TrueLayer\Models\MerchantAccountBeneficiary;
use TrueLayer\Models\SortCodeAccountNumber;

class BeneficiaryBuilder implements BeneficiaryBuilderInterface
{
    /**
     * @return SortCodeAccountNumber
     */
    public function sortCodeAccountNumber(): SortCodeAccountNumber
    {
        return new SortCodeAccountNumber();
    }

    /**
     * @return MerchantAccountBeneficiary
     */
    public function ibanAccount(): MerchantAccountBeneficiary
    {
        return new MerchantAccountBeneficiary();
    }

    /**
     * @return MerchantAccountBeneficiary
     */
    public function merchantAccount(): MerchantAccountBeneficiary
    {
        return new MerchantAccountBeneficiary();
    }
}
