<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payment\Refund;

use TrueLayer\Attributes\Field;
use TrueLayer\Interfaces\Payment\RefundFailedInterface;

final class RefundFailed extends RefundRetrieved implements RefundFailedInterface
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
    protected string $failureReason;

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
}
