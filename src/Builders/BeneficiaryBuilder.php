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
     * @return SortCodeAccountNumber
     */
    public function sortCodeAccountNumber(): SortCodeAccountNumber
    {
        return new SortCodeAccountNumber();
    }

    /**
     * @return IbanAccountBeneficiary
     */
    public function ibanAccount(): IbanAccountBeneficiary
    {
        return new IbanAccountBeneficiary();
    }

    /**
     * @return MerchantAccountBeneficiary
     */
    public function merchantAccount(): MerchantAccountBeneficiary
    {
        return new MerchantAccountBeneficiary();
    }
}
