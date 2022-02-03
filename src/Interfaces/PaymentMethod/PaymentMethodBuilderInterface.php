<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\PaymentMethod;

interface PaymentMethodBuilderInterface
{
    /**
     * @return BankTransferPaymentMethodInterface
     */
    public function bankTransfer(): BankTransferPaymentMethodInterface;
}
