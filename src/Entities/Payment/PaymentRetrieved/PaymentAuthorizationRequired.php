<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payment\PaymentRetrieved;

use TrueLayer\Entities\Payment\PaymentRetrieved;
use TrueLayer\Interfaces\Payment\PaymentAuthorizationRequiredInterface;

final class PaymentAuthorizationRequired extends PaymentRetrieved implements PaymentAuthorizationRequiredInterface
{

}
