<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Webhook;

interface PayoutExecutedEventInterface extends PayoutEventInterface
{
    /**
     * Get the payment execution date.
     *
     * @return \DateTimeInterface
     */
    public function getExecutedAt(): \DateTimeInterface;
}
