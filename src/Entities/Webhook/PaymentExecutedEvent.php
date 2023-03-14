<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Webhook;

use TrueLayer\Interfaces\Webhook\PaymentExecutedEventInterface;

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
     * @return mixed[]
     */
    protected function casts(): array
    {
        return \array_merge(parent::casts(), [
            'executed_at' => \DateTimeInterface::class,
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
        ]);
    }

    /**
     * @return mixed[]
     */
    protected function rules(): array
    {
        return \array_merge(parent::rules(), [
            'executed_at' => 'required|date',
            'settlement_risk' => 'nullable|array',
            'settlement_risk.category' => 'nullable|string',
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
}
