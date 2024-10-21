<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Webhook;

use TrueLayer\Interfaces\Payment\PaymentSourceInterface;
use TrueLayer\Interfaces\Webhook\PaymentAuthorizedEventInterface;
use TrueLayer\Interfaces\Webhook\PaymentMethod\PaymentMethodInterface;

class PaymentAuthorizedEvent extends PaymentEvent implements PaymentAuthorizedEventInterface
{
    /**
     * @var \DateTimeInterface
     */
    protected \DateTimeInterface $authorizedAt;

    /**
     * @var PaymentSourceInterface
     */
    protected PaymentSourceInterface $paymentSource;

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
            'authorized_at' => \DateTimeInterface::class,
            'payment_source' => PaymentSourceInterface::class,
            'payment_method' => PaymentMethodInterface::class,
        ]);
    }

    /**
     * @return mixed[]
     */
    protected function arrayFields(): array
    {
        return \array_merge(parent::arrayFields(), [
            'authorized_at',
            'payment_source',
            'payment_method',
        ]);
    }

    /**
     * @return \DateTimeInterface
     */
    public function getAuthorizedAt(): \DateTimeInterface
    {
        return $this->authorizedAt;
    }

    /**
     * @return PaymentSourceInterface|null
     */
    public function getPaymentSource(): ?PaymentSourceInterface
    {
        return $this->paymentSource ?? null;
    }

    /**
     * @return PaymentMethodInterface
     */
    public function getPaymentMethod(): PaymentMethodInterface
    {
        return $this->paymentMethod;
    }
}
