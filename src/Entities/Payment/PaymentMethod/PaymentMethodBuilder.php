<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payment\PaymentMethod;

use TrueLayer\Entities\Entity;
use TrueLayer\Entities\EntityBuilder;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Exceptions\ValidationException;
use TrueLayer\Interfaces\PaymentMethod\BankTransferPaymentMethodInterface;
use TrueLayer\Interfaces\PaymentMethod\PaymentMethodBuilderInterface;

class PaymentMethodBuilder extends EntityBuilder implements PaymentMethodBuilderInterface
{
    /**
     * @return BankTransferPaymentMethodInterface
     * @throws InvalidArgumentException
     * @throws ValidationException
     */
    public function bankTransfer(): BankTransferPaymentMethodInterface
    {
        return $this->entityFactory->make(BankTransferPaymentMethodInterface::class);
    }
}
