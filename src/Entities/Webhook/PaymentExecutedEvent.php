<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Webhook;

use TrueLayer\Interfaces\Payment\PaymentSourceInterface;
use TrueLayer\Interfaces\Webhook\PaymentExecutedEventInterface;
use TrueLayer\Interfaces\Webhook\PaymentMethod\PaymentMethodInterface;

class PaymentExecutedEvent extends PaymentEvent implements PaymentExecutedEventInterface
{
    /**
     * @var \DateTimeInterface
     */
    protected \DateTimeInterface $executedAt;

    /**
     * @var string
     */
    protected string $settlementRiskCategory;

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
            'executed_at' => \DateTimeInterface::class,
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
            'executed_at',
            'settlement_risk.category' => 'settlement_risk_category',
            'payment_source',
            'payment_method',
        ]);
    }

    /**
     * @return \DateTimeInterface
     */
    public function getExecutedAt(): \DateTimeInterface
    {
        return $this->executedAt;
    }

    /**
     * @return string|null
     */
    public function getSettlementRiskCategory(): ?string
    {
        return $this->settlementRiskCategory ?? null;
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
