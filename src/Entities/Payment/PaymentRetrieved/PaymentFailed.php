<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payment\PaymentRetrieved;

use TrueLayer\Constants\PaymentStatus;
use TrueLayer\Interfaces\Payment\PaymentFailedInterface;
use TrueLayer\Validation\AllowedConstant;

final class PaymentFailed extends _PaymentWithAuthorizationConfig implements PaymentFailedInterface
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
     * @return mixed[]
     */
    protected function rules(): array
    {
        return \array_merge(parent::rules(), [
            'failed_at' => 'required|date',
            'failure_stage' => ['required', AllowedConstant::in(PaymentStatus::class)],
            'failure_reason' => 'nullable|string',
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
