<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payout;

use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Interfaces\Beneficiary\ExternalAccountBeneficiaryInterface;

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
     * @param mixed[] $data
     *
     * @return PayoutBeneficiaryInterface
     * @throws InvalidArgumentException
     *
     */
    public function fill(array $data): PayoutBeneficiaryInterface;
}
