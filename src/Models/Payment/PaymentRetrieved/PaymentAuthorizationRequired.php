<?php

declare(strict_types=1);

namespace TrueLayer\Models\Payment\PaymentRetrieved;

use TrueLayer\Contracts\Payment\PaymentAuthorizationRequiredInterface;
use TrueLayer\Models\Payment\PaymentRetrieved;

final class PaymentAuthorizationRequired extends PaymentRetrieved implements PaymentAuthorizationRequiredInterface
{
}
