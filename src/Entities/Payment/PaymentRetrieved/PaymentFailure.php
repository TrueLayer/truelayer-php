<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payment\PaymentRetrieved;

use TrueLayer\Attributes\Field;

abstract class PaymentFailure extends _PaymentWithAuthorizationConfig
{
    /**
     * @var \DateTimeInterface
     */
    #[Field]
    protected \DateTimeInterface $failedAt;

    /**
     * @var string
     */
    #[Field]
    protected string $failureStage;

    /**
     * @var string
     */
    #[Field]
    protected string $failureReason;
    
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
