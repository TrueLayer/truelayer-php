<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payment;

interface PaymentFailedInterface extends PaymentFailureInterface
{
    /**
     * @return \DateTimeInterface|null
     */
    public function getCreditableAt(): ?\DateTimeInterface;
}
