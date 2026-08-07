<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Webhook;

interface PayoutAttemptFailedEventInterface extends PayoutEventInterface
{
    /**
     * Get the date and time the payout attempt failed.
     *
     * @return \DateTimeInterface
     */
    public function getFailedAt(): \DateTimeInterface;

    /**
     * Get the reason the payout attempt failed.
     *
     * @return string|null
     */
    public function getFailureReason(): ?string;

    /**
     * Get the unique identifier for the scheme.
     *
     * @return string|null
     */
    public function getSchemeId(): ?string;

    /**
     * Get details about the retry attempt.
     *
     * @return PayoutAttemptFailedRetryInterface|null
     */
    public function getRetry(): ?PayoutAttemptFailedRetryInterface;
}
