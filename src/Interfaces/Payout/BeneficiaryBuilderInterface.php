<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payout;

use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Interfaces\Payout\ExternalAccountBeneficiaryInterface;

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
     * @return PayoutBeneficiaryInterface
     */
    public function fill(array $data): PayoutBeneficiaryInterface;
}
