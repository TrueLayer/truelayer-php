<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Webhook;

use TrueLayer\Interfaces\Payment\PaymentSourceInterface;
use TrueLayer\Interfaces\Webhook\PaymentMethod\PaymentMethodInterface;

interface PaymentSettledEventInterface extends PaymentEventInterface
{
    /**
     * The payment's settlement risk-rating. Only available for closed-loop EUR payments in Private Beta and subject to change.
     * Will be one of "low_risk", "high_risk".
     *
     * @return string|null
     */
    public function getSettlementRiskCategory(): ?string;

    /**
     * @return PaymentSourceInterface
     */
    public function getPaymentSource(): PaymentSourceInterface;

    /**
     * @return \DateTimeInterface
     */
    public function getSettledAt(): \DateTimeInterface;

    /**
     * Get the method of the payment. T
     * ype can be "mandate" or "bank_transfer".
     * Mandates contain mandate_id and bank transfers contain provider_id and scheme_id, if available.
     *
     * @return PaymentMethodInterface
     */
    public function getPaymentMethod(): PaymentMethodInterface;
}
