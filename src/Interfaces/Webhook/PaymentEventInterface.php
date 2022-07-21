<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Webhook;


use TrueLayer\Interfaces\Webhook\PaymentMethod\PaymentMethodInterface;

interface PaymentEventInterface extends EventInterface
{
    /**
     * Get the unique ID for the payment
     * @return string
     */
    public function getPaymentId(): string;

    /**
     * Get the method of the payment. T
     * ype can be "mandate" or "bank_transfer".
     * Mandates contain mandate_id and bank transfers contain provider_id and scheme_id, if available.
     * @return PaymentMethodInterface
     */
    public function getPaymentMethod(): PaymentMethodInterface;
}
