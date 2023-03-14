<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payment;

interface RefundExecutedInterface extends RefundRetrievedInterface
{
    /**
     * @return \DateTimeInterface
     */
    public function getExecutedAt(): \DateTimeInterface;
}
