<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Webhook\PaymentMethod;

interface MandatePaymentMethodInterface extends PaymentMethodInterface
{
    /**
     * @return string
     */
    public function getMandateId(): string;

    /**
     * @return string|null
     */
    public function getReference(): ?string;
}
