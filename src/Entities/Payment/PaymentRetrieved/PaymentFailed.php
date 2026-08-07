<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payment\PaymentRetrieved;

use TrueLayer\Interfaces\Payment\PaymentFailedInterface;

final class PaymentFailed extends PaymentFailure implements PaymentFailedInterface
{
    /**
     * @return mixed[]
     */
    protected function casts(): array
    {
        return \array_merge_recursive(parent::casts(), [
            'creditable_at' => \DateTimeInterface::class,
            'settlement_stalled_at' => \DateTimeInterface::class,
            'reversed_at' => \DateTimeInterface::class,
        ]);
    }

    /**
     * @return mixed[]
     */
    protected function arrayFields(): array
    {
        return \array_merge(parent::arrayFields(), [
            'creditable_at',
            'settlement_stalled_at',
            'reversed_at',
            'reversal_reason',
        ]);
    }

    /**
     * @var \DateTimeInterface
     */
    protected \DateTimeInterface $creditableAt;

    /**
     * @var \DateTimeInterface
     */
    protected \DateTimeInterface $settlementStalledAt;

    /**
     * @var \DateTimeInterface
     */
    protected \DateTimeInterface $reversedAt;

    /**
     * @var string
     */
    protected string $reversalReason;

    /**
     * @return \DateTimeInterface|null
     */
    public function getCreditableAt(): ?\DateTimeInterface
    {
        return $this->creditableAt ?? null;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getSettlementStalledAt(): ?\DateTimeInterface
    {
        return $this->settlementStalledAt ?? null;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getReversedAt(): ?\DateTimeInterface
    {
        return $this->reversedAt ?? null;
    }

    /**
     * @return string|null
     */
    public function getReversalReason(): ?string
    {
        return $this->reversalReason ?? null;
    }
}
