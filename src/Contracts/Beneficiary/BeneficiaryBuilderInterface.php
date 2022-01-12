<?php

declare(strict_types=1);

namespace TrueLayer\Contracts\Beneficiary;

use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Exceptions\ValidationException;
use TrueLayer\Models\Beneficiary\IbanBeneficiary;
use TrueLayer\Models\Beneficiary\MerchantBeneficiary;
use TrueLayer\Models\Beneficiary\ScanBeneficiary;

interface BeneficiaryBuilderInterface
{
    /**
     * @return ScanBeneficiary
     */
    public function sortCodeAccountNumber(): ScanBeneficiary;

    /**
     * @return IbanBeneficiary
     */
//    public function ibanAccount(): IbanBeneficiary;

    /**
     * @return MerchantBeneficiary
     */
    public function merchantAccount(): MerchantBeneficiary;

    /**
     * @param mixed[] $data
     *
     * @throws InvalidArgumentException
     * @throws ValidationException
     *
     * @return BeneficiaryInterface
     */
    public function fill(array $data): BeneficiaryInterface;
}
