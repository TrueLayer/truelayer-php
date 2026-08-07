<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payment;

use TrueLayer\Interfaces\ArrayableInterface;

interface PaymentFailedAttemptInterface extends ArrayableInterface
{
    /**
     * @return int
     */
    public function getAttemptNumber(): int;

    /**
     * @return \DateTimeInterface
     */
    public function getFailedAt(): \DateTimeInterface;

    /**
     * @return string
     */
    public function getFailureReason(): string;
}
