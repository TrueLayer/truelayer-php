<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Webhook;

interface PaymentCreditableEventInterface extends PaymentEventInterface
{
    /**
     * Get the date and time that TrueLayer determined that the payment was ready to be credited.
     *
     * @return \DateTimeInterface
     */
    public function getCreditableAt(): \DateTimeInterface;
}
