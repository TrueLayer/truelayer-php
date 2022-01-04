<?php

declare(strict_types=1);

namespace TrueLayer\Contracts\Beneficiary;

use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Exceptions\ValidationException;
use TrueLayer\Services\Beneficiary\IbanAccountBeneficiary;
use TrueLayer\Services\Beneficiary\MerchantAccountBeneficiary;
use TrueLayer\Services\Beneficiary\SortCodeAccountNumber;

interface BeneficiaryBuilderInterface
{
    /**
     *
     * @return SortCodeAccountNumber
     */
    public function sortCodeAccountNumber(): SortCodeAccountNumber;

    /**
     *
     * @return IbanAccountBeneficiary
     */
    public function ibanAccount(): IbanAccountBeneficiary;

    /**
     *
     * @return MerchantAccountBeneficiary
     */
    public function merchantAccount(): MerchantAccountBeneficiary;

    /**
     * @param mixed[] $data
     * @return BeneficiaryInterface
     * @throws InvalidArgumentException
     * @throws ValidationException
     */
    public function fill(array $data): BeneficiaryInterface;
}
