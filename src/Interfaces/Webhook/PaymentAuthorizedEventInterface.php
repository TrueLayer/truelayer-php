<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Webhook;

use TrueLayer\Interfaces\Payment\PaymentSourceInterface;
use TrueLayer\Interfaces\Webhook\PaymentMethod\PaymentMethodInterface;

interface PaymentAuthorizedEventInterface extends PaymentEventInterface
{
    /**
     * Get the date and time the payment was authorized.
     *
     * @return \DateTimeInterface
     */
    public function getAuthorizedAt(): \DateTimeInterface;

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
