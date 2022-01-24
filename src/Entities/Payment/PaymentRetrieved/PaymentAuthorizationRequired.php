<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payment\PaymentRetrieved;

use TrueLayer\Interfaces\Payment\PaymentAuthorizationRequiredInterface;
use TrueLayer\Entities\Payment\PaymentRetrieved;

final class PaymentAuthorizationRequired extends PaymentRetrieved implements PaymentAuthorizationRequiredInterface
{
}
