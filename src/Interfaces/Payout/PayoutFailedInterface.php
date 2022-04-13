<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payout;

use DateTimeInterface;

interface PayoutFailedInterface extends PayoutRetrievedInterface
{
    /**
     * @return DateTimeInterface
     */
    public function getFailedAt(): DateTimeInterface;

    /**
     * @return string|null
     */
    public function getFailureReason(): ?string;
}
