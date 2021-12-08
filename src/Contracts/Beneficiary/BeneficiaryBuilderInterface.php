<?php

namespace TrueLayer\Contracts\Beneficiary;

use TrueLayer\Services\Beneficiary\IbanAccountBeneficiary;
use TrueLayer\Services\Beneficiary\MerchantAccountBeneficiary;
use TrueLayer\Services\Beneficiary\SortCodeAccountNumber;

interface BeneficiaryBuilderInterface
{
    /**
     * @param array $data
     *
     * @return SortCodeAccountNumber
     */
    public function sortCodeAccountNumber(array $data = []): SortCodeAccountNumber;

    /**
     * @param array $data
     *
     * @return IbanAccountBeneficiary
     */
    public function ibanAccount(array $data = []): IbanAccountBeneficiary;

    /**
     * @param array $data
     *
     * @return MerchantAccountBeneficiary
     */
    public function merchantAccount(array $data = []): MerchantAccountBeneficiary;
}
