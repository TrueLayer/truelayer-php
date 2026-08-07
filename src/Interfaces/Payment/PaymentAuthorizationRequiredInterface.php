<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payment;

/**
 * A payment yet to be authorized. This is the first status for a payment that requires further actions to
 * be authorized.
 */
interface PaymentAuthorizationRequiredInterface extends PaymentRetrievedInterface
{
}
