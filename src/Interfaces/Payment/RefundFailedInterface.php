<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payment;

/**
 * A failed refund.
 */
interface RefundFailedInterface extends RefundRetrievedInterface
{
    /**
     * The date and time the refund failed.
     *
     * @return \DateTimeInterface
     */
    public function getFailedAt(): \DateTimeInterface;

    /**
     * The reason the refund failed. Handle unexpected values gracefully as an unknown failure.
     *
     * @return string|null
     */
    public function getFailureReason(): ?string;
}
