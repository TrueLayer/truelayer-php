<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payment;

use TrueLayer\Interfaces\ArrayableInterface;

/**
 * A failed payment attempt, recorded when payment retries are enabled. Contains details about when the
 * attempt failed and why.
 */
interface PaymentFailedAttemptInterface extends ArrayableInterface
{
    /**
     * The sequential number of this payment attempt.
     *
     * @return int
     */
    public function getAttemptNumber(): int;

    /**
     * The date and time this payment attempt failed.
     *
     * @return \DateTimeInterface
     */
    public function getFailedAt(): \DateTimeInterface;

    /**
     * The reason this attempt failed. Handle unexpected values gracefully as an unknown failure.
     *
     * @return string
     */
    public function getFailureReason(): string;
}
