<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Webhook;

interface PaymentReversedEventInterface extends PaymentEventInterface
{
    /**
     * Get the date and time the payment was reversed.
     *
     * @return \DateTimeInterface
     */
    public function getReversedAt(): \DateTimeInterface;

    /**
     * Get the reason for the payment reversal.
     * Can be one of "corrected", "failed", "disputed".
     *
     * @return string
     */
    public function getReversalReason(): string;
}
