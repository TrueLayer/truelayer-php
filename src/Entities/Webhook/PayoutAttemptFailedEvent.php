<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Webhook;

use TrueLayer\Interfaces\Webhook\PayoutAttemptFailedEventInterface;
use TrueLayer\Interfaces\Webhook\PayoutAttemptFailedRetryInterface;

class PayoutAttemptFailedEvent extends PayoutEvent implements PayoutAttemptFailedEventInterface
{
    /**
     * @var \DateTimeInterface
     */
    protected \DateTimeInterface $failedAt;

    /**
     * @var string
     */
    protected string $failureReason;

    /**
     * @var string
     */
    protected string $schemeId;

    /**
     * @var PayoutAttemptFailedRetryInterface
     */
    protected PayoutAttemptFailedRetryInterface $retry;

    /**
     * @return mixed[]
     */
    protected function casts(): array
    {
        return \array_merge(parent::casts(), [
            'failed_at' => \DateTimeInterface::class,
            'retry' => PayoutAttemptFailedRetryInterface::class,
        ]);
    }

    /**
     * @return mixed[]
     */
    protected function arrayFields(): array
    {
        return \array_merge(parent::arrayFields(), [
            'failed_at',
            'failure_reason',
            'scheme_id',
            'retry',
        ]);
    }

    /**
     * @return \DateTimeInterface
     */
    public function getFailedAt(): \DateTimeInterface
    {
        return $this->failedAt;
    }

    /**
     * @return string|null
     */
    public function getFailureReason(): ?string
    {
        return $this->failureReason ?? null;
    }

    /**
     * @return string|null
     */
    public function getSchemeId(): ?string
    {
        return $this->schemeId ?? null;
    }

    /**
     * @return PayoutAttemptFailedRetryInterface|null
     */
    public function getRetry(): ?PayoutAttemptFailedRetryInterface
    {
        return $this->retry ?? null;
    }
}
