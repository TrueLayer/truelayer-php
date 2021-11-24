<?php

namespace TrueLayer\Contracts\Builders;

use TrueLayer\Models\MerchantAccountBeneficiary;
use TrueLayer\Models\SortCodeAccountNumber;

interface BeneficiaryBuilderInterface
{
    /**
     * @return SortCodeAccountNumber
     */
    public function sortCodeAccountNumber(): SortCodeAccountNumber;

    /**
     * @return MerchantAccountBeneficiary
     */
    public function ibanAccount(): MerchantAccountBeneficiary;

    /**
     * @return MerchantAccountBeneficiary
     */
    public function merchantAccount(): MerchantAccountBeneficiary;
}
