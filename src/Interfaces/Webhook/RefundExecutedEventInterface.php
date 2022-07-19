<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Webhook;

use DateTimeInterface;

interface RefundExecutedEventInterface extends RefundEventInterface
{
    /**
     * Get the payment execution date
     * @return DateTimeInterface
     */
    public function getExecutedAt(): DateTimeInterface;
}
