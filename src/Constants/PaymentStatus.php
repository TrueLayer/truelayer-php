<?php

declare(strict_types=1);

namespace TrueLayer\Constants;

class PaymentStatus
{
    public const AUTHORIZATION_REQUIRED = 'authorization_required';
    public const AUTHORIZING = 'authorizing';
    public const AUTHORIZED = 'authorized';
    public const EXECUTED = 'executed';
    public const FAILED = 'failed';
    public const SETTLED = 'settled';
}
