<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payout\PayoutRetrieved;

use DateTimeInterface;
use TrueLayer\Entities\Payout\PayoutRetrieved;
use TrueLayer\Interfaces\Payout\PayoutFailedInterface;

final class PayoutFailed extends PayoutRetrieved implements PayoutFailedInterface
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
    protected function casts(): array
    {
        return \array_merge(parent::casts(), [
            'failed_at' => DateTimeInterface::class,
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
