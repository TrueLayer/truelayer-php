<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Webhook;

use DateTimeInterface;

interface PaymentExecutedEventInterface extends PaymentEventInterface
{
    /**
     * Get the payment execution date
     * @return DateTimeInterface
     */
    public function getExecutedAt(): DateTimeInterface;

    /**
     * The payment's settlement risk-rating. Only available for closed-loop EUR payments in Private Beta and subject to change.
     * Will be one of "low_risk", "high_risk"
     * @return string|null
     */
    public function getSettlementRiskCategory(): ?string;
}
