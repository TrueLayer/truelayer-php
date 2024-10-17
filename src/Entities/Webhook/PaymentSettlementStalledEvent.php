<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Webhook;

use TrueLayer\Interfaces\Webhook\PaymentSettlementStalledEventInterface;

class PaymentSettlementStalledEvent extends PaymentEvent implements PaymentSettlementStalledEventInterface
{
    /**
     * @var \DateTimeInterface
     */
    protected \DateTimeInterface $settlementStalledAt;

    /**
     * @return mixed[]
     */
    protected function casts(): array
    {
        return \array_merge(parent::casts(), [
            'settlement_stalled_at' => \DateTimeInterface::class,
        ]);
    }

    /**
     * @return mixed[]
     */
    protected function arrayFields(): array
    {
        return \array_merge(parent::arrayFields(), [
            'settlement_stalled_at',
        ]);
    }

    /**
     * @return \DateTimeInterface
     */
    public function getSettlementStalledAt(): \DateTimeInterface
    {
        return $this->settlementStalledAt;
    }
}
