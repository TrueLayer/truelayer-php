<?php

namespace TrueLayer\Contracts\Builders;

use TrueLayer\Models\IbanAccountBeneficiary;
use TrueLayer\Models\MerchantAccountBeneficiary;
use TrueLayer\Models\SortCodeAccountNumber;

interface BeneficiaryBuilderInterface
{
    /**
     * @param array $data
     * @return SortCodeAccountNumber
     */
    public function sortCodeAccountNumber(array $data = []): SortCodeAccountNumber;

    /**
     * @param array $data
     * @return IbanAccountBeneficiary
     */
    public function ibanAccount(array $data = []): IbanAccountBeneficiary;

    /**
     * @param array $data
     * @return MerchantAccountBeneficiary
     */
    public function merchantAccount(array $data = []): MerchantAccountBeneficiary;
}
