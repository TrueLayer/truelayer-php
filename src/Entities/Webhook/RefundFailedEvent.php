<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Webhook;

use DateTimeInterface;
use TrueLayer\Interfaces\Webhook\RefundFailedEventInterface;

class RefundFailedEvent extends RefundEvent implements RefundFailedEventInterface
{
    /**
     * @var DateTimeInterface
     */
    protected DateTimeInterface $failedAt;

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
            'failed_at' => DateTimeInterface::class,
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
        ]);
    }

    /**
     * @return mixed[]
     */
    protected function rules(): array
    {
        return \array_merge(parent::rules(), [
            'failed_at' => 'required|date',
            'failure_reason' => 'nullable|string',
        ]);
    }

    /**
     * @return DateTimeInterface
     */
    public function getFailedAt(): DateTimeInterface
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
}
