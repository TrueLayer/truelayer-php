<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Webhook\PaymentMethod;

interface MandatePaymentMethodInterface
{
    /**
     * @return string
     */
    public function getMandateId(): string;
}
