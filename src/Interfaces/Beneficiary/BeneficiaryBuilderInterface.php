<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Beneficiary;

use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Exceptions\ValidationException;
use TrueLayer\Entities\Beneficiary\IbanBeneficiary;

interface BeneficiaryBuilderInterface
{
    /**
     * @return ScanBeneficiaryInterface
     */
    public function sortCodeAccountNumber(): ScanBeneficiaryInterface;

    /**
     * @return IbanBeneficiary
     */
//    public function ibanAccount(): IbanBeneficiary;

    /**
     * @return MerchantBeneficiaryInterface
     */
    public function merchantAccount(): MerchantBeneficiaryInterface;

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
