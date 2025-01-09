<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Webhook;

use TrueLayer\Interfaces\Payment\PaymentSourceInterface;
use TrueLayer\Interfaces\Webhook\PaymentFailedEventInterface;
use TrueLayer\Interfaces\Webhook\PaymentMethod\PaymentMethodInterface;

class PaymentFailedEvent extends PaymentEvent implements PaymentFailedEventInterface
{
    /**
     * @var \DateTimeInterface
     */
    protected \DateTimeInterface $failedAt;

    /**
     * @var string
     */
    protected string $failureStage;

    /**
     * @var string
     */
    protected string $failureReason;

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
        return \array_merge_recursive(parent::casts(), [
            'failed_at' => \DateTimeInterface::class,
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
            'failed_at',
            'failure_stage',
            'failure_reason',
            'payment_source',
            'payment_method',
        ]);
    }

    /**
     * @return \DateTimeInterface
     */
    public function getFailedAt(): \DateTimeInterface
    {
        return $this->failedAt;
    }

    /**
     * @return string
     */
    public function getFailureStage(): string
    {
        return $this->failureStage;
    }

    /**
     * @return string|null
     */
    public function getFailureReason(): ?string
    {
        return $this->failureReason ?? null;
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
