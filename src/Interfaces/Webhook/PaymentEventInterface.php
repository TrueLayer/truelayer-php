<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Webhook;

interface PaymentEventInterface extends EventInterface
{
    /**
     * Get the unique ID for the payment.
     *
     * @return string
     */
    public function getPaymentId(): string;

    /**
     * Get the metadata associated with the payment.
     *
     * @return array<string, string>
     */
    public function getMetadata(): array;
}
