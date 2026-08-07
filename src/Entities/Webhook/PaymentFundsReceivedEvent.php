<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Webhook;

use TrueLayer\Interfaces\Webhook\PaymentFundsReceivedEventInterface;

class PaymentFundsReceivedEvent extends PaymentEvent implements PaymentFundsReceivedEventInterface
{
    /**
     * @var \DateTimeInterface
     */
    protected \DateTimeInterface $fundsReceivedAt;

    /**
     * @return mixed[]
     */
    protected function casts(): array
    {
        return \array_merge(parent::casts(), [
            'funds_received_at' => \DateTimeInterface::class,
        ]);
    }

    /**
     * @return mixed[]
     */
    protected function arrayFields(): array
    {
        return \array_merge(parent::arrayFields(), [
            'funds_received_at',
        ]);
    }

    /**
     * @return \DateTimeInterface
     */
    public function getFundsReceivedAt(): \DateTimeInterface
    {
        return $this->fundsReceivedAt;
    }
}
