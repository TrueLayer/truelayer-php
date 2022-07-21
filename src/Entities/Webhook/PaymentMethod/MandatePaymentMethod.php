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
     * @var string[]
     */
    protected array $arrayFields = [
        'type',
        'mandate_id',
    ];

    protected array $rules = [
        'type' => 'required|string',
        'mandate_id' => 'required|string',
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
}
