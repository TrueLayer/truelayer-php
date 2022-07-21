<?php

declare(strict_types=1);

namespace TrueLayer\Constants;

class CustomHeaders
{
    public const VERSION = 'Tl-Version';
    public const TRACE_ID = 'Tl-Trace-Id';
    public const SIGNATURE = 'Tl-Signature';
    public const IDEMPOTENCY_KEY = 'Idempotency-Key';
    public const CORRELATION_ID = 'X-Tl-Correlation-Id';
    public const WEBHOOK_TIMESTAMP = 'X-Tl-Webhook-Timestamp';
    public const TL_AGENT = 'TL-Agent';
}
