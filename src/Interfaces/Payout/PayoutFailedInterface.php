<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payout;

interface PayoutFailedInterface extends PayoutRetrievedInterface
{
    /**
     * @return \DateTimeInterface
     */
    public function getFailedAt(): \DateTimeInterface;

    /**
     * @return string|null
     */
    public function getFailureReason(): ?string;
}
