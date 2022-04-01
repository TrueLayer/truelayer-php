<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payout;

use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Exceptions\ValidationException;
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
     * @throws ValidationException
     * @throws InvalidArgumentException
     *
     * @return PayoutBeneficiaryInterface
     */
    public function fill(array $data): PayoutBeneficiaryInterface;
}
