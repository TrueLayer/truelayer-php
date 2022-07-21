<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Webhook\Beneficiary;

interface PaymentSourceBeneficiaryInterface extends BeneficiaryInterface
{
    /**
     * @return string
     */
    public function getPaymentSourceId(): string;

    /**
     * @return string
     */
    public function getUserId(): string;
}
