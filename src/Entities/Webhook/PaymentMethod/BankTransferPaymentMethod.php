<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Webhook\PaymentMethod;

use TrueLayer\Entities\Entity;
use TrueLayer\Interfaces\Webhook\PaymentMethod\BankTransferPaymentMethodInterface;

class BankTransferPaymentMethod extends Entity implements BankTransferPaymentMethodInterface
{
    /**
     * @var string
     */
    protected string $type;

    /**
     * @var string
     */
    protected string $providerId;

    /**
     * @var string
     */
    protected string $schemeId;

    /**
     * @var string[]
     */
    protected array $arrayFields = [
        'type',
        'provider_id',
        'scheme_id',
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
    public function getProviderId(): string
    {
        return $this->providerId;
    }

    /**
     * @return string
     */
    public function getSchemeId(): string
    {
        return $this->schemeId;
    }
}
