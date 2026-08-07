<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Webhook;

interface PaymentDisputedEventInterface extends PaymentEventInterface
{
    /**
     * Get the date and time that TrueLayer received an indemnity claim for the payment.
     *
     * @return \DateTimeInterface
     */
    public function getDisputedAt(): \DateTimeInterface;

    /**
     * Get the reason that the indemnity claim was made against the payment.
     *
     * @return string|null
     */
    public function getDisputedReason(): ?string;

    /**
     * Get the date and time that the dispute will expire and, by default, be resolved in favour of the end-user.
     *
     * @return \DateTimeInterface
     */
    public function getExpiresAt(): \DateTimeInterface;
}
