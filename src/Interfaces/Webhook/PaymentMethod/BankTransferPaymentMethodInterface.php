<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Webhook\PaymentMethod;

interface BankTransferPaymentMethodInterface extends PaymentMethodInterface
{
    /**
     * @return string
     */
    public function getProviderId(): string;

    /**
     * @return string
     */
    public function getSchemeId(): string;
}
