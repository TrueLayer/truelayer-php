<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payout;

interface PayoutExecutedInterface extends PayoutRetrievedInterface
{
    /**
     * @return \DateTimeInterface
     */
    public function getExecutedAt(): \DateTimeInterface;
}
