<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Webhook;

use DateTimeInterface;

interface PayoutFailedEventInterface extends PayoutEventInterface
{
    /**
     * Get the date of payout failure
     * @return DateTimeInterface
     */
    public function getFailedAt(): DateTimeInterface;

    /**
     * Get the reason for the payout failure
     * @return string|null
     */
    public function getFailureReason(): ?string;

}
