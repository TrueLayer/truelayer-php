<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payment;

/**
 * A failed payment attempt - it was authorized successfully, but the money did not move. Only reachable
 * when payment retries are enabled; the payment may still succeed on a later attempt.
 */
interface PaymentAttemptFailedInterface extends PaymentFailureInterface
{
}
