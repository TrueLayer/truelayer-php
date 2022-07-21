<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Webhook\PaymentMethod;

interface PaymentMethodInterface
{
    /**
     * @return string
     */
    public function getType(): string;
}
