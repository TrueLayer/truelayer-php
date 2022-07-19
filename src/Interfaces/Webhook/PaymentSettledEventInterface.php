<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Webhook;

use DateTimeInterface;
use TrueLayer\Interfaces\Payment\PaymentSourceInterface;

interface PaymentSettledEventInterface extends EventInterface
{
    /**
     * The payment's settlement risk-rating. Only available for closed-loop EUR payments in Private Beta and subject to change.
     * Will be one of "low_risk", "high_risk"
     * @return string|null
     */
    public function getSettlementRiskCategory(): ?string;

    /**
     * @return PaymentSourceInterface
     */
    public function getPaymentSource(): PaymentSourceInterface;

    /**
     * @return DateTimeInterface
     */
    public function getSettledAt(): DateTimeInterface;

}
