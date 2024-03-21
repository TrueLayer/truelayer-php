<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Webhook;

use TrueLayer\Interfaces\Webhook\PaymentEventInterface;
use TrueLayer\Interfaces\Webhook\PaymentMethod\PaymentMethodInterface;

class PaymentEvent extends Event implements PaymentEventInterface
{
    /**
     * @var string
     */
    protected string $paymentId;

    /**
     * @var PaymentMethodInterface
     */
    protected PaymentMethodInterface $paymentMethod;

    /**
     * @return mixed[]
     */
    protected function casts(): array
    {
        return \array_merge(parent::casts(), [
            'payment_method' => PaymentMethodInterface::class,
        ]);
    }

    /**
     * @return mixed[]
     */
    protected function arrayFields(): array
    {
        return \array_merge(parent::arrayFields(), [
            'payment_id',
            'payment_method',
        ]);
    }

    /**
     * @return string
     */
    public function getPaymentId(): string
    {
        return $this->paymentId;
    }

    /**
     * @return PaymentMethodInterface
     */
    public function getPaymentMethod(): PaymentMethodInterface
    {
        return $this->paymentMethod;
    }
}
