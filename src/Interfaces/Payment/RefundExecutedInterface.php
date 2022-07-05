<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payment;

use DateTimeInterface;

interface RefundExecutedInterface extends RefundRetrievedInterface
{
    /**
     * @return DateTimeInterface
     */
    public function getExecutedAt(): DateTimeInterface;
}
