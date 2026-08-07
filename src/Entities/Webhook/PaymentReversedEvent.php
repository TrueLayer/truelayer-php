<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Webhook;

use TrueLayer\Interfaces\Webhook\PaymentReversedEventInterface;

class PaymentReversedEvent extends PaymentEvent implements PaymentReversedEventInterface
{
    /**
     * @var \DateTimeInterface
     */
    protected \DateTimeInterface $reversedAt;

    /**
     * @var string
     */
    protected string $reversalReason;

    /**
     * @return mixed[]
     */
    protected function casts(): array
    {
        return \array_merge(parent::casts(), [
            'reversed_at' => \DateTimeInterface::class,
        ]);
    }

    /**
     * @return mixed[]
     */
    protected function arrayFields(): array
    {
        return \array_merge(parent::arrayFields(), [
            'reversed_at',
            'reversal_reason',
        ]);
    }

    /**
     * @return \DateTimeInterface
     */
    public function getReversedAt(): \DateTimeInterface
    {
        return $this->reversedAt;
    }

    /**
     * @return string
     */
    public function getReversalReason(): string
    {
        return $this->reversalReason;
    }
}
