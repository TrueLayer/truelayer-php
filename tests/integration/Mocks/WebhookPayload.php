<?php

declare(strict_types=1);

namespace TrueLayer\Tests\Integration\Mocks;

class WebhookPayload
{
    public static function paymentAuthorized(): string
    {
        return '{
            "type": "payment_authorized",
            "event_version": 1,
            "event_id": "b8d4dda0-ff2c-4d77-a6da-4615e4bad941",
            "payment_id": "60c0a60ed8d7-4e5b-ac79-401b1d8a8633",
            "payment_method": {
                "type": "bank_transfer",
                "provider_id": "mock-payments-gb-redirect",
                "scheme_id": "faster_payments_service"
            },
            "authorized_at": "2021-12-25T15:00:00.000Z",
            "payment_source": {
                "account_identifiers": [
                  {
                    "type": "sort_code_account_number",
                    "sort_code": "111111",
                    "account_number": "00000111"
                  },
                  {
                    "type": "iban",
                    "iban": "GB11CLRB01011100000111"
                  }
                ],
                "id": "1f111d3c-9427-43be-1111-1111219d111c",
                "account_holder_name": "HOLDER NAME"
            },
            "metadata": {
                "key1": "value1",
                "key2": "value2"
            }
        }';
    }

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
          },
          "settlement_risk": {
            "category": "low_risk"
          },
          "payment_source": {
            "account_identifiers": [
              {
                "type": "sort_code_account_number",
                "sort_code": "111111",
                "account_number": "00000111"
              },
              {
                "type": "iban",
                "iban": "GB11CLRB01011100000111"
              }
            ],
            "id": "1f111d3c-9427-43be-1111-1111219d111c",
            "account_holder_name": "HOLDER NAME"
          },
          "metadata": {
            "key1": "value1",
            "key2": "value2"
          }
        }';
    }

    public static function paymentExecutedMandate(): string
    {
        return '{
          "type": "payment_executed",
          "event_version": 1,
          "event_id": "b8d4dda0-ff2c-4d77-a6da-4615e4bad941",
          "payment_id": "60c0a60ed8d7-4e5b-ac79-401b1d8a8633",
          "executed_at": "2021-12-25T15:00:00.000Z",
          "payment_method": {
            "type": "mandate",
            "mandate_id": "d65f3521-fa55-44fc-9a75-ba43456de7f2",
            "reference": "test"
          }
        }';
    }

    public static function paymentSettled(): string
    {
        return '{
          "type": "payment_settled",
          "event_version": 1,
          "event_id": "b8d4dda0-ff2c-4d77-a6da-4615e4bad941",
          "payment_id": "60c0a60ed8d7-4e5b-ac79-401b1d8a8633",
          "settled_at": "2021-12-25T15:00:00.000Z",
          "payment_method": {
            "type": "bank_transfer",
            "provider_id": "mock-payments-gb-redirect",
            "scheme_id": "faster_payments_service"
          },
          "payment_source": {
            "account_identifiers": [
              {
                "type": "sort_code_account_number",
                "sort_code": "111111",
                "account_number": "00000111"
              },
              {
                "type": "iban",
                "iban": "GB11CLRB01011100000111"
              }
            ],
            "id": "1f111d3c-9427-43be-1111-1111219d111c",
            "account_holder_name": "HOLDER NAME"
          },
          "settlement_risk": {
            "category": "low_risk"
          },
          "metadata": {
            "key1": "value1",
            "key2": "value2"
          }
        }';
    }

    public static function paymentFailed(): string
    {
        return '{
          "type": "payment_failed",
          "event_version": 1,
          "event_id": "b8d4dda0-ff2c-4d77-a6da-4615e4bad941",
          "payment_id": "60c0a60ed8d7-4e5b-ac79-401b1d8a8633",
          "failed_at": "2021-12-25T15:00:00.000Z",
          "failure_stage": "authorizing",
          "failure_reason": "provider_rejected",
          "payment_method": {
            "type": "bank_transfer",
            "provider_id": "mock-payments-gb-redirect",
            "scheme_id": "faster_payments_service"
          },
          "payment_source": {
            "account_identifiers": [
              {
                "type": "sort_code_account_number",
                "sort_code": "111111",
                "account_number": "00000111"
              },
              {
                "type": "iban",
                "iban": "GB11CLRB01011100000111"
              }
            ],
            "id": "1f111d3c-9427-43be-1111-1111219d111c",
            "account_holder_name": "HOLDER NAME"
          },
          "metadata": {
            "key1": "value1",
            "key2": "value2"
          }
        }';
    }

    public static function paymentCreditable(): string
    {
        return '{
            "type": "payment_creditable",
            "event_version": 1,
            "event_id": "b8d4dda0-ff2c-4d77-a6da-4615e4bad941",
            "payment_id": "60c0a60ed8d7-4e5b-ac79-401b1d8a8633",
            "creditable_at": "2024-10-14T14:02:26.825Z",
            "metadata": {
                "key1": "value1",
                "key2": "value2"
              }
        }';
    }

    public static function paymentSettlementStalled(): string
    {
        return '{
            "type": "payment_settlement_stalled",
            "event_version": 1,
            "event_id": "b8d4dda0-ff2c-4d77-a6da-4615e4bad941",
            "payment_id": "60c0a60ed8d7-4e5b-ac79-401b1d8a8633",
            "settlement_stalled_at": "2024-10-14T14:02:26.825Z",
            "metadata": {
                "key1": "value1",
                "key2": "value2"
              }
        }';
    }

    public static function paymentNoMetadata(): string
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
          },
          "settlement_risk": {
            "category": "low_risk"
          }
        }';
    }

    public static function unknownType(): string
    {
        return '{
          "type": "foo",
          "event_version": 1,
          "event_id": "b8d4dda0-ff2c-4d77-a6da-4615e4bad941"
        }';
    }

    public static function unknownSubType(): string
    {
        return '{
          "type": "payment_executed",
          "event_version": 1,
          "event_id": "b8d4dda0-ff2c-4d77-a6da-4615e4bad941",
          "payment_id": "60c0a60ed8d7-4e5b-ac79-401b1d8a8633",
          "executed_at": "2021-12-25T15:00:00.000Z",
          "payment_method": {
            "type": "foo"
          }
        }';
    }

    public static function refundExecuted(): string
    {
        return '{
          "type": "refund_executed",
          "event_version": 1,
          "event_id": "f6321c84-1797-4e66-acd4-d768c09f9edf",
          "refund_id": "af386a24-e5e6-4508-a4e4-82d4bc4e3677",
          "payment_id": "dfb531ca-8e25-4753-bc23-0c7eeb8d4f29",
          "executed_at": "2021-12-25T15:00:00.000Z",
          "scheme_id": "faster_payments_service"
        }';
    }

    public static function refundFailed(): string
    {
        return '{
          "type": "refund_failed",
          "event_version": 1,
          "event_id": "f6321c84-1797-4e66-acd4-d768c09f9edf",
          "refund_id": "af386a24-e5e6-4508-a4e4-82d4bc4e3677",
          "payment_id": "dfb531ca-8e25-4753-bc23-0c7eeb8d4f29",
          "failed_at": "2021-12-25T15:00:00.000Z",
          "failure_reason": "insufficient_funds"
        }';
    }

    public static function payoutExecuted(): string
    {
        return '{
          "type": "payout_executed",
          "event_version": 1,
          "event_id": "b8d4dda0-ff2c-4d77-a6da-4615e4bad941",
          "payout_id": "0cd1b0f7-71bc-4d24-b209-95259dadcc20",
          "executed_at": "2021-12-25T15:00:00.000Z"
        }';
    }

    public static function payoutFailed(): string
    {
        return '{
          "type": "payout_failed",
          "event_version": 1,
          "event_id": "b8d4dda0-ff2c-4d77-a6da-4615e4bad941",
          "payout_id": "0cd1b0f7-71bc-4d24-b209-95259dadcc20",
          "failed_at": "2021-12-25T15:00:00.000Z",
          "failure_reason": "scheme_error"
        }';
    }

    public static function payoutExecutedClosedLoop(): string
    {
        return '{
          "type": "payout_executed",
          "event_version": 1,
          "event_id": "b8d4dda0-ff2c-4d77-a6da-4615e4bad941",
          "payout_id": "0cd1b0f7-71bc-4d24-b209-95259dadcc20",
          "executed_at": "2021-12-25T15:00:00.000Z",
          "beneficiary": {
            "type": "payment_source",
            "payment_source_id": "4a59c822-3bfb-42ba-9202-b6d89988a195",
            "user_id": "a0977be8-c406-4f75-bb81-b5ca0689b29b"
          }
        }';
    }

    public static function payoutFailedClosedLoop(): string
    {
        return '{
          "type": "payout_failed",
          "event_version": 1,
          "event_id": "b8d4dda0-ff2c-4d77-a6da-4615e4bad941",
          "payout_id": "0cd1b0f7-71bc-4d24-b209-95259dadcc20",
          "failed_at": "2021-12-25T15:00:00.000Z",
          "failure_reason": "scheme_error",
          "beneficiary": {
            "type": "payment_source",
            "payment_source_id": "4a59c822-3bfb-42ba-9202-b6d89988a195",
            "user_id": "a0977be8-c406-4f75-bb81-b5ca0689b29b"
          }
        }';
    }

    public static function payoutExecutedBusinessAccount(): string
    {
        return '{
          "type": "payout_executed",
          "event_version": 1,
          "event_id": "b8d4dda0-ff2c-4d77-a6da-4615e4bad941",
          "payout_id": "0cd1b0f7-71bc-4d24-b209-95259dadcc20",
          "executed_at": "2021-12-25T15:00:00.000Z",
          "beneficiary": {
            "type": "business_account"
          }
        }';
    }

    public static function payoutFailedBusinessAccount(): string
    {
        return '{
          "type": "payout_failed",
          "event_version": 1,
          "event_id": "b8d4dda0-ff2c-4d77-a6da-4615e4bad941",
          "payout_id": "0cd1b0f7-71bc-4d24-b209-95259dadcc20",
          "failed_at": "2021-12-25T15:00:00.000Z",
          "failure_reason": "scheme_error",
          "beneficiary": {
            "type": "business_account"
          }
        }';
    }
}
