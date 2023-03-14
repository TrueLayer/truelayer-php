<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Webhook;

use TrueLayer\Interfaces\Payment\PaymentSourceInterface;
use TrueLayer\Interfaces\Webhook\PaymentSettledEventInterface;
use TrueLayer\Validation\ValidType;

class PaymentSettledEvent extends PaymentEvent implements PaymentSettledEventInterface
{
    /**
     * @var \DateTimeInterface
     */
    protected \DateTimeInterface $settledAt;

    /**
     * @var string
     */
    protected string $settlementRiskCategory;

    /**
     * @var PaymentSourceInterface
     */
    protected PaymentSourceInterface $paymentSource;

    /**
     * @return mixed[]
     */
    protected function casts(): array
    {
        return \array_merge_recursive(parent::casts(), [
            'settled_at' => \DateTimeInterface::class,
            'payment_source' => PaymentSourceInterface::class,
        ]);
    }

    /**
     * @return mixed[]
     */
    protected function rules(): array
    {
        return \array_merge(parent::rules(), [
            'settled_at' => 'required|date',
            'settlement_risk.category' => 'nullable|string',
            'payment_source' => [ValidType::of(PaymentSourceInterface::class)],
        ]);
    }

    /**
     * @return mixed[]
     */
    protected function arrayFields(): array
    {
        return \array_merge(parent::arrayFields(), [
            'settled_at',
            'settlement_risk.category' => 'settlement_risk_category',
            'payment_source',
        ]);
    }

    /**
     * @return \DateTimeInterface
     */
    public function getSettledAt(): \DateTimeInterface
    {
        return $this->settledAt;
    }

    /**
     * @return string|null
     */
    public function getSettlementRiskCategory(): ?string
    {
        return $this->settlementRiskCategory ?? null;
    }

    /**
     * @return PaymentSourceInterface
     */
    public function getPaymentSource(): PaymentSourceInterface
    {
        return $this->paymentSource;
    }
}
