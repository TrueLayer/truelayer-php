<?php

declare(strict_types=1);

namespace TrueLayer\Tests\Integration\Mocks;

class WebhookPayload
{
    public static function paymentExecuted(): string
    {
        return '{
          "type": "payment_executed",
          "event_version": 1,
          "event_id": "b8d4dda0-ff2c-4d77-a6da-4615e4bad941",
          "payment_id": "60c0a60ed8d7-4e5b-ac79-401b1d8a8633",
          "executed_at": "2021-12-25T15:00:00.000Z",
          "payment_method": {
            "type": "bank_transfer",
            "provider_id": "mock-payments-gb-redirect",
            "scheme_id": "faster_payments_service"
          }
        }';
    }
}
