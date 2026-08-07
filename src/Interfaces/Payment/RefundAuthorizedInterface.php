<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payment;

/**
 * An authorized refund - no more actions required, waiting for outcome.
 */
interface RefundAuthorizedInterface extends RefundRetrievedInterface
{
}
