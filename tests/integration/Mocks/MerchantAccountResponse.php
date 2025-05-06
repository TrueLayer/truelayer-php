<?php

declare(strict_types=1);

namespace TrueLayer\Tests\Integration\Mocks;

use GuzzleHttp\Psr7\Response;

class MerchantAccountResponse
{
    public static function accountsList(): Response
    {
        return new Response(200, [], '{"items":[{"id":"a2dcee6d-7a00-414d-a1e6-8a2b23169e00","currency":"EUR","account_identifiers":[{"type":"iban","iban":"GB38CLRB04066200003769"}],"available_balance_in_minor":100000,"current_balance_in_minor":100000,"account_holder_name":"John Doe"},{"id":"822f8dfe-0874-481d-b966-5b14f767792f","currency":"GBP","account_identifiers":[{"type":"sort_code_account_number","sort_code":"040662","account_number":"00003209"},{"type":"iban","iban":"GB26CLRB04066200003209"}],"available_balance_in_minor":100001,"current_balance_in_minor":100001,"account_holder_name":"John Doe"}]}');
    }

    public static function account(): Response
    {
        return new Response(200, [], '{"id":"a2dcee6d-7a00-414d-a1e6-8a2b23169e00","currency":"EUR","account_identifiers":[{"type":"iban","iban":"GB38CLRB04066200003769"}],"available_balance_in_minor":100000,"current_balance_in_minor":100000,"account_holder_name":"John Doe"}');
    }

    public static function transactions(): Response
    {
        return new Response(200, [], '{
          "items": [
            {
              "type": "merchant_account_payment",
              "id": "string",
              "currency": "GBP",
              "amount_in_minor": 0,
              "status": "settled",
              "settled_at": "2025-05-03T10:23:00Z",
              "payment_source": {
                "id": "e2b41c9d-176k-67aa-b2da-fe1a2b97253c",
                "account_holder_name": "string",
                "account_identifiers": [
                  {
                    "type": "sort_code_account_number",
                    "sort_code": 560029,
                    "account_number": 26207729
                  },
                  {
                    "type": "iban",
                    "iban": "GB32CLRB04066800012315"
                  },
                  {
                    "type": "nrb",
                    "nrb": "string"
                  }
                ]
              },
              "payment_id": "0afd1f6a-f611-48ce-9488-321129bb3a70",
              "metadata": {
                "prop1": "value1",
                "prop2": "value2"
              }
            },
            {
              "type": "external_payment",
              "id": "string",
              "currency": "GBP",
              "amount_in_minor": 0,
              "status": "settled",
              "settled_at": "2025-05-03T14:37:00Z",
              "remitter": {
                "account_holder_name": "string",
                "reference": "string",
                "account_identifiers": [
                  {
                    "type": "sort_code_account_number",
                    "sort_code": 560029,
                    "account_number": 26207729
                  },
                  {
                    "type": "iban",
                    "iban": "GB32CLRB04066800012315"
                  },
                  {
                    "type": "nrb",
                    "nrb": "string"
                  }
                ]
              },
              "return_for": {
                "type": "identified",
                "returned_id": "0cd1b0f7-71bc-4d24-b209-95259dadcc20"
              }
            },
            {
              "type": "payout",
              "id": "string",
              "currency": "GBP",
              "amount_in_minor": 0,
              "status": "pending",
              "created_at": "2025-05-02T11:11:11Z",
              "beneficiary": {
                "type": "external_account",
                "reference": "string",
                "account_holder_name": "string",
                "account_identifiers": [
                  {
                    "type": "sort_code_account_number",
                    "sort_code": 560029,
                    "account_number": 26207729
                  },
                  {
                    "type": "iban",
                    "iban": "GB32CLRB04066800012315"
                  },
                  {
                    "type": "nrb",
                    "nrb": "string"
                  }
                ]
              },
              "context_code": "withdrawal",
              "payout_id": "0cd1b0f7-71bc-4d24-b209-95259dadcc20",
              "metadata": {
                "prop1": "value1",
                "prop2": "value2"
              }
            },
            {
              "type": "payout",
              "id": "string",
              "currency": "GBP",
              "amount_in_minor": 0,
              "status": "executed",
              "created_at": "2025-05-10T12:32:12Z",
              "executed_at": "2025-05-10T13:32:12Z",
              "beneficiary": {
                "type": "external_account",
                "reference": "string",
                "account_holder_name": "string",
                "account_identifiers": [
                  {
                    "type": "sort_code_account_number",
                    "sort_code": 560029,
                    "account_number": 26207729
                  },
                  {
                    "type": "iban",
                    "iban": "GB32CLRB04066800012315"
                  },
                  {
                    "type": "nrb",
                    "nrb": "string"
                  }
                ]
              },
              "context_code": "withdrawal",
              "payout_id": "0cd1b0f7-71bc-4d24-b209-95259dadcc20",
              "returned_by": "0cd1b0f7-71bc-4d24-b209-95259dadcc20",
              "scheme_id": "faster_payments_service",
              "metadata": {
                "prop1": "value1",
                "prop2": "value2"
              }
            },
            {
              "type": "refund",
              "id": "string",
              "currency": "GBP",
              "amount_in_minor": 0,
              "status": "pending",
              "created_at": "2025-05-14T12:32:12Z",
              "executed_at": "2025-05-15T10:32:12Z",
              "beneficiary": {
                "type": "payment_source",
                "payment_source_id": "string",
                "user_id": "string",
                "reference": "string",
                "account_holder_name": "string",
                "account_identifiers": [
                  {
                    "type": "sort_code_account_number",
                    "sort_code": 560029,
                    "account_number": 26207729
                  },
                  {
                    "type": "iban",
                    "iban": "GB32CLRB04066800012315"
                  },
                  {
                    "type": "nrb",
                    "nrb": "string"
                  }
                ]
              },
              "context_code": "withdrawal",
              "refund_id": "43d12d0f-d775-410f-aaff-482200c17017",
              "payment_id": "0afd1f6a-f611-48ce-9488-321129bb3a70",
              "returned_by": "0cd1b0f7-71bc-4d24-b209-95259dadcc20",
              "scheme_id": "faster_payments_service",
              "metadata": {
                "prop1": "value1",
                "prop2": "value2"
              }
            }
          ],
          "pagination": {
            "next_cursor": "bWFuZGF0ZXM6MmUwNDk0MTMK"
          }
          }'
        );
    }
}
