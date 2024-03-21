<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Webhook;

use TrueLayer\Interfaces\Webhook\PaymentFailedEventInterface;

class PaymentFailedEvent extends PaymentEvent implements PaymentFailedEventInterface
{
    /**
     * @var \DateTimeInterface
     */
    protected \DateTimeInterface $failedAt;

    /**
     * @var string
     */
    protected string $failureStage;

    /**
     * @var string
     */
    protected string $failureReason;

    /**
     * @return mixed[]
     */
    protected function casts(): array
    {
        return \array_merge_recursive(parent::casts(), [
            'failed_at' => \DateTimeInterface::class,
        ]);
    }

    /**
     * @return mixed[]
     */
    protected function arrayFields(): array
    {
        return \array_merge(parent::arrayFields(), [
            'failed_at',
            'failure_stage',
            'failure_reason',
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
     * @return string
     */
    public function getFailureStage(): string
    {
        return $this->failureStage;
    }

    /**
     * @return string|null
     */
    public function getFailureReason(): ?string
    {
        return $this->failureReason ?? null;
    }
}
