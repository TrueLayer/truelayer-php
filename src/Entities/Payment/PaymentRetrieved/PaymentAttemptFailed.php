<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payment\PaymentRetrieved;

use TrueLayer\Interfaces\Payment\PaymentAttemptFailedInterface;

final class PaymentAttemptFailed extends PaymentFailure implements PaymentAttemptFailedInterface
{
}
