<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payment\PaymentRetrieved;

use TrueLayer\Entities\Entity;
use TrueLayer\Interfaces\Payment\PaymentFailedAttemptInterface;

final class PaymentFailedAttempt extends Entity implements PaymentFailedAttemptInterface
{
    /**
     * @var int
     */
    protected int $attemptNumber;

    /**
     * @var \DateTimeInterface
     */
    protected \DateTimeInterface $failedAt;

    /**
     * @var string
     */
    protected string $failureReason;

    /**
     * @var string[]
     */
    protected array $casts = [
        'failed_at' => \DateTimeInterface::class,
    ];

    /**
     * @var array|string[]
     */
    protected array $arrayFields = [
        'attempt_number',
        'failed_at',
        'failure_reason',
    ];

    /**
     * @return int
     */
    public function getAttemptNumber(): int
    {
        return $this->attemptNumber;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getFailedAt(): \DateTimeInterface
    {
        return $this->failedAt;
    }

    /**
     * @return string
     */
    public function getFailureReason(): string
    {
        return $this->failureReason;
    }
}
