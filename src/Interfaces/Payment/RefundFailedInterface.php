<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payment;

interface RefundFailedInterface extends RefundRetrievedInterface
{
    /**
     * @return \DateTimeInterface
     */
    public function getFailedAt(): \DateTimeInterface;

    /**
     * @return string|null
     */
    public function getFailureReason(): ?string;
}
