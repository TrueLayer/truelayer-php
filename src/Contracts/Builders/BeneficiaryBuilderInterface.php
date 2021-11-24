<?php

namespace TrueLayer\Contracts\Builders;

use TrueLayer\Models\IbanAccountBeneficiary;
use TrueLayer\Models\MerchantAccountBeneficiary;
use TrueLayer\Models\SortCodeAccountNumber;

interface BeneficiaryBuilderInterface
{
    /**
     * @return SortCodeAccountNumber
     */
    public function sortCodeAccountNumber(): SortCodeAccountNumber;

    /**
     * @return IbanAccountBeneficiary
     */
    public function ibanAccount(): IbanAccountBeneficiary;

    /**
     * @return MerchantAccountBeneficiary
     */
    public function merchantAccount(): MerchantAccountBeneficiary;
}
