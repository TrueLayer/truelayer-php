<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Webhook;

use TrueLayer\Interfaces\Webhook\RefundEventInterface;

class RefundEvent extends Event implements RefundEventInterface
{
    /**
     * @var string
     */
    protected string $paymentId;

    /**
     * @var string
     */
    protected string $refundId;

    /**
     * @var array<string, string>
     */
    protected array $metadata;

    /**
     * @return mixed[]
     */
    protected function arrayFields(): array
    {
        return \array_merge(parent::arrayFields(), [
            'payment_id',
            'refund_id',
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
     * @return string
     */
    public function getRefundId(): string
    {
        return $this->refundId;
    }

    /**
     * @return array<string, string>
     */
    public function getMetadata(): array
    {
        return $this->metadata ?? [];
    }
}
