<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Webhook;

interface RefundEventInterface extends EventInterface
{
    /**
     * Get the unique ID for the refund.
     *
     * @return string
     */
    public function getRefundId(): string;

    /**
     * Get the unique ID for the payment.
     *
     * @return string
     */
    public function getPaymentId(): string;

    /**
     * Get the metadata associated with the refund.
     *
     * @return array<string, string>
     */
    public function getMetadata(): array;
}
