<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Webhook\PaymentMethod;

interface BankTransferPaymentMethodInterface extends PaymentMethodInterface
{
    /**
     * @return string|null
     */
    public function getProviderId(): ?string;

    /**
     * @return string|null
     */
    public function getSchemeId(): ?string;
}
