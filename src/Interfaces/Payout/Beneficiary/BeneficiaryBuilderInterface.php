<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payout\Beneficiary;

use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Interfaces\Payout\Beneficiary\ExternalAccountBeneficiaryInterface;

interface BeneficiaryBuilderInterface
{
    /**
     * @return ExternalAccountBeneficiaryInterface
     */
    public function externalAccount(): ExternalAccountBeneficiaryInterface;

    /**
     * @return PaymentSourceBeneficiaryInterface
     */
    public function paymentSource(): PaymentSourceBeneficiaryInterface;

    /**
     * @return BusinessAccountBeneficiaryInterface
     */
    public function businessAccount(): BusinessAccountBeneficiaryInterface;

    /**
     * @param mixed[] $data
     *
     * @throws InvalidArgumentException
     *
     * @return BeneficiaryInterface
     */
    public function fill(array $data): BeneficiaryInterface;
}
