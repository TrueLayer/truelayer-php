<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payout;

use DateTimeInterface;

interface PayoutExecutedInterface extends PayoutRetrievedInterface
{
    /**
     * @return DateTimeInterface
     */
    public function getExecutedAt(): DateTimeInterface;
}
