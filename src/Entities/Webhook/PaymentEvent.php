<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Webhook;

use TrueLayer\Interfaces\Webhook\PaymentEventInterface;

class PaymentEvent extends Event implements PaymentEventInterface
{
    /**
     * @var string
     */
    protected string $paymentId;

    /**
     * @var array<string, string>
     */
    protected array $metadata = [];

    /**
     * @return mixed[]
     */
    protected function arrayFields(): array
    {
        return \array_merge(parent::arrayFields(), [
            'payment_id',
            'metadata',
        ]);
    }

    /**
     * @return string
     */
    public function getPaymentId(): string
    {
        return $this->paymentId;
    }

    /**
     * @return array<string, string>
     */
    public function getMetadata(): array
    {
        return $this->metadata;
    }
}
