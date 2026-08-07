<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Webhook;

use TrueLayer\Interfaces\Webhook\PaymentDisputedEventInterface;

class PaymentDisputedEvent extends PaymentEvent implements PaymentDisputedEventInterface
{
    /**
     * @var \DateTimeInterface
     */
    protected \DateTimeInterface $disputedAt;

    /**
     * @var string
     */
    protected string $disputedReason;

    /**
     * @var \DateTimeInterface
     */
    protected \DateTimeInterface $expiresAt;

    /**
     * @return mixed[]
     */
    protected function casts(): array
    {
        return \array_merge(parent::casts(), [
            'disputed_at' => \DateTimeInterface::class,
            'expires_at' => \DateTimeInterface::class,
        ]);
    }

    /**
     * @return mixed[]
     */
    protected function arrayFields(): array
    {
        return \array_merge(parent::arrayFields(), [
            'disputed_at',
            'disputed_reason',
            'expires_at',
        ]);
    }

    /**
     * @return \DateTimeInterface
     */
    public function getDisputedAt(): \DateTimeInterface
    {
        return $this->disputedAt;
    }

    /**
     * @return string|null
     */
    public function getDisputedReason(): ?string
    {
        return $this->disputedReason ?? null;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getExpiresAt(): \DateTimeInterface
    {
        return $this->expiresAt;
    }
}
