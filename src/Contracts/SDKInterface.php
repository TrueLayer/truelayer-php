<?php

namespace TrueLayer\Contracts;

use TrueLayer\Contracts\Builders\BeneficiaryBuilderInterface;
use TrueLayer\Contracts\Builders\PaymentRequestBuilderInterface;
use TrueLayer\Contracts\Models\UserInterface;

interface SDKInterface
{
    /**
     * @return UserInterface
     */
    public function user(): UserInterface;

    /**
     * @return BeneficiaryBuilderInterface
     */
    public function beneficiary(): BeneficiaryBuilderInterface;

    /**
     * @return PaymentRequestBuilderInterface
     */
    public function payment(): PaymentRequestBuilderInterface;
}
