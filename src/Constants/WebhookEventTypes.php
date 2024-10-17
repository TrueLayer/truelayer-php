<?php

declare(strict_types=1);

namespace TrueLayer\Constants;

class WebhookEventTypes
{
    public const PAYMENT_AUTHORIZED = 'payment_authorized';
    public const PAYMENT_EXECUTED = 'payment_executed';
    public const PAYMENT_FAILED = 'payment_failed';
    public const PAYMENT_SETTLED = 'payment_settled';
    public const PAYMENT_CREDITABLE = 'payment_creditable';
    public const PAYMENT_SETTLEMENT_STALLED = 'payment_settlement_stalled';
    public const EXTERNAL_PAYMENT_RECEIVED = 'external_payment_received';

    public const REFUND_EXECUTED = 'refund_executed';
    public const REFUND_FAILED = 'refund_failed';

    public const PAYOUT_EXECUTED = 'payout_executed';
    public const PAYOUT_FAILED = 'payout_failed';
}
