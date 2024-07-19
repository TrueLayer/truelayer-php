<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payment\PaymentRetrieved;

use TrueLayer\Interfaces\Payment\PaymentFailedInterface;

final class PaymentFailed extends PaymentFailure implements PaymentFailedInterface
{
}
