<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payment;

/**
 * An executed refund.
 */
interface RefundExecutedInterface extends RefundRetrievedInterface
{
    /**
     * The date and time the refund executed.
     *
     * @return \DateTimeInterface
     */
    public function getExecutedAt(): \DateTimeInterface;
}
