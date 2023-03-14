<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Webhook;

interface RefundFailedEventInterface extends RefundEventInterface
{
    /**
     * Get the date of refund failure.
     *
     * @return \DateTimeInterface
     */
    public function getFailedAt(): \DateTimeInterface;

    /**
     * Get the reason for the refund failure.
     *
     * @return string|null
     */
    public function getFailureReason(): ?string;
}
