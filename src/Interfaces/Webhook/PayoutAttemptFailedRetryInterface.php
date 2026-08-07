<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Webhook;

use TrueLayer\Interfaces\ArrayableInterface;

interface PayoutAttemptFailedRetryInterface extends ArrayableInterface
{
    /**
     * Get the number of retry attempts that have been made so far.
     *
     * @return int
     */
    public function getAttemptsMade(): int;

    /**
     * Get the number of retry attempts remaining.
     *
     * @return int
     */
    public function getAttemptsRemaining(): int;

    /**
     * Whether the payout is scheduled for rerouting to a different scheme.
     *
     * @return bool
     */
    public function isScheduledForRerouting(): bool;

    /**
     * Get the date and time of the next retry attempt.
     *
     * @return \DateTimeInterface
     */
    public function getNextAttemptAt(): \DateTimeInterface;
}
