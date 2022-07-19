<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Webhook;

use DateTimeInterface;
use TrueLayer\Interfaces\Webhook\PaymentExecutedEventInterface;

class PaymentExecutedEvent extends PaymentEvent implements PaymentExecutedEventInterface
{
    /**
     * @var DateTimeInterface
     */
    protected DateTimeInterface $executedAt;

    /**
     * @var array
     */
    protected array $settlementRisk = [];

    /**
     * @return mixed[]
     */
    protected function casts(): array
    {
        return \array_merge_recursive(parent::casts(), [
            'executed_at' => DateTimeInterface::class,
        ]);
    }

    /**
     * @return mixed[]
     */
    protected function arrayFields(): array
    {
        return \array_merge(parent::arrayFields(), [
            'executed_at',
            'settlement_risk.category'
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
     * @return DateTimeInterface
     */
    public function getExecutedAt(): DateTimeInterface
    {
        return $this->executedAt;
    }

    /**
     * @return string|null
     */
    public function getSettlementRiskCategory(): ?string
    {
        return $this->settlementRisk['category'] ?? null;
    }
}
