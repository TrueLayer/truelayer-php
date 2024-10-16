<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Webhook;

use TrueLayer\Interfaces\Webhook\PaymentCreditableEventInterface;

class PaymentCreditableEvent extends PaymentEvent implements PaymentCreditableEventInterface
{
    /**
     * @var \DateTimeInterface
     */
    protected \DateTimeInterface $creditableAt;

    /**
     * @return mixed[]
     */
    protected function casts(): array
    {
        return \array_merge(parent::casts(), [
            'creditable_at' => \DateTimeInterface::class,
        ]);
    }

    /**
     * @return mixed[]
     */
    protected function arrayFields(): array
    {
        return \array_merge(parent::arrayFields(), [
            'creditable_at',
        ]);
    }

    /**
     * @return \DateTimeInterface
     */
    public function getCreditableAt(): \DateTimeInterface
    {
        return $this->creditableAt;
    }
}
