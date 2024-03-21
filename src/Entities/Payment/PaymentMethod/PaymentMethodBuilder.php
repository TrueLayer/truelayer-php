<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payment\PaymentMethod;

use TrueLayer\Entities\EntityBuilder;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Interfaces\PaymentMethod\BankTransferPaymentMethodInterface;
use TrueLayer\Interfaces\PaymentMethod\PaymentMethodBuilderInterface;

class PaymentMethodBuilder extends EntityBuilder implements PaymentMethodBuilderInterface
{
    /**
     * @return BankTransferPaymentMethodInterface
     *
     * @throws InvalidArgumentException
     */
    public function bankTransfer(): BankTransferPaymentMethodInterface
    {
        return $this->entityFactory->make(BankTransferPaymentMethodInterface::class);
    }
}
