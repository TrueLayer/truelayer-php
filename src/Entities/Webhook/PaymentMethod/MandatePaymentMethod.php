<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Webhook\PaymentMethod;

use TrueLayer\Entities\Entity;
use TrueLayer\Interfaces\Webhook\PaymentMethod\MandatePaymentMethodInterface;

class MandatePaymentMethod extends Entity implements MandatePaymentMethodInterface
{
    /**
     * @var string
     */
    protected string $type;

    /**
     * @var string
     */
    protected string $mandateId;

    /**
     * @var string
     */
    protected string $reference;

    /**
     * @var string[]
     */
    protected array $arrayFields = [
        'type',
        'mandate_id',
        'reference',
    ];

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getMandateId(): string
    {
        return $this->mandateId;
    }

    /**
     * @return string|null
     */
    public function getReference(): ?string
    {
        return $this->reference ?? null;
    }
}
