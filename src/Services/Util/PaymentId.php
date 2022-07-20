<?php

declare(strict_types=1);

namespace TrueLayer\Services\Util;

use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Interfaces\Payment\PaymentCreatedInterface;
use TrueLayer\Interfaces\Payment\PaymentRetrievedInterface;

class PaymentId
{
    /**
     * @param string|PaymentCreatedInterface|PaymentRetrievedInterface $payment
     *
     * @throws InvalidArgumentException
     *
     * @return string
     */
    public static function find($payment): string
    {
        if ($payment instanceof PaymentCreatedInterface || $payment instanceof PaymentRetrievedInterface) {
            return $payment->getId();
        }

        if (\is_string($payment)) {
            return $payment;
        }

        // @phpstan-ignore-next-line
        throw new InvalidArgumentException('Payment must be string|PaymentCreatedInterface|PaymentRetrievedInterface');
    }
}
