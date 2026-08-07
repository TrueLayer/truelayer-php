<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Webhook;

use TrueLayer\Entities\Entity;
use TrueLayer\Interfaces\Webhook\PayoutAttemptFailedRetryInterface;

final class PayoutAttemptFailedRetry extends Entity implements PayoutAttemptFailedRetryInterface
{
    /**
     * @var int
     */
    protected int $attemptsMade;

    /**
     * @var int
     */
    protected int $attemptsRemaining;

    /**
     * @var bool
     */
    protected bool $scheduledForRerouting;

    /**
     * @var \DateTimeInterface
     */
    protected \DateTimeInterface $nextAttemptAt;

    /**
     * @var string[]
     */
    protected array $casts = [
        'next_attempt_at' => \DateTimeInterface::class,
    ];

    /**
     * @var array|string[]
     */
    protected array $arrayFields = [
        'attempts_made',
        'attempts_remaining',
        'scheduled_for_rerouting',
        'next_attempt_at',
    ];

    /**
     * @return int
     */
    public function getAttemptsMade(): int
    {
        return $this->attemptsMade;
    }

    /**
     * @return int
     */
    public function getAttemptsRemaining(): int
    {
        return $this->attemptsRemaining;
    }

    /**
     * @return bool
     */
    public function isScheduledForRerouting(): bool
    {
        return $this->scheduledForRerouting;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getNextAttemptAt(): \DateTimeInterface
    {
        return $this->nextAttemptAt;
    }
}
