<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Webhook;

use DateTimeInterface;

interface PaymentFailedEventInterface extends PaymentEventInterface
{
    /**
     * Get the date of payment failure
     * @return DateTimeInterface
     */
    public function getFailedAt(): DateTimeInterface;

    /**
     * An enum identifying where the payment failed in its lifecycle.
     * Can be one of "authorization_required", "authorizing", "authorized"
     * @return string
     */
    public function getFailureStage(): string;

    /**
     * The reason the payment failed.
     * Example values to expect: "authorization_failed", "rejected", "provider_error", "provider_rejected", "internal_server_error", "canceled", "expired".
     * Implementations should expect other values, since there may be more failure reasons added in future
     * @return string|null
     */
    public function getFailureReason(): ?string;

}
