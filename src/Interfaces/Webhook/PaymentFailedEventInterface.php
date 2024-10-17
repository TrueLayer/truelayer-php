<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Webhook;

use TrueLayer\Interfaces\Payment\PaymentSourceInterface;
use TrueLayer\Interfaces\Webhook\PaymentMethod\PaymentMethodInterface;

interface PaymentFailedEventInterface extends PaymentEventInterface
{
    /**
     * Get the date of payment failure.
     *
     * @return \DateTimeInterface
     */
    public function getFailedAt(): \DateTimeInterface;

    /**
     * An enum identifying where the payment failed in its lifecycle.
     * Can be one of "authorization_required", "authorizing", "authorized".
     *
     * @return string
     */
    public function getFailureStage(): string;

    /**
     * The reason the payment failed.
     * Example values to expect: "authorization_failed", "rejected", "provider_error", "provider_rejected", "internal_server_error", "canceled", "expired".
     * Implementations should expect other values, since there may be more failure reasons added in future.
     *
     * @return string|null
     */
    public function getFailureReason(): ?string;

    /**
     * @return PaymentSourceInterface|null
     */
    public function getPaymentSource(): ?PaymentSourceInterface;

    /**
     * Get the method of the payment. T
     * ype can be "mandate" or "bank_transfer".
     * Mandates contain mandate_id and bank transfers contain provider_id and scheme_id, if available.
     *
     * @return PaymentMethodInterface
     */
    public function getPaymentMethod(): PaymentMethodInterface;
}
