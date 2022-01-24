<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payment\PaymentRetrieved;

use TrueLayer\Interfaces\Payment\PaymentAuthorizedInterface;

final class PaymentAuthorized extends _PaymentWithAuthorizationConfig implements PaymentAuthorizedInterface
{
}
