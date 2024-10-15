<?php

declare(strict_types=1);

namespace TrueLayer\Tests\Integration\Mocks;

use GuzzleHttp\Psr7\Response;

class PayoutResponse
{
    public static function pending(): Response
    {
        return new Response(200, [], '{"id":"cbc98b01-6d4e-4137-a6fc-1659a26e5c55","merchant_account_id":"822f8dfe-0874-481d-b966-5b14f767792f","amount_in_minor":1,"currency":"GBP","beneficiary":{"type":"external_account","account_holder_name":"Test","account_identifier":{"type":"sort_code_account_number","sort_code":"121212","account_number":"12345678"},"reference":"Test reference"},"status":"pending","created_at":"2022-04-01T19:47:12.642232Z"}');
    }

    public static function authorized(): Response
    {
        return new Response(200, [], '{"id":"cbc98b01-6d4e-4137-a6fc-1659a26e5c55","merchant_account_id":"822f8dfe-0874-481d-b966-5b14f767792f","amount_in_minor":1,"currency":"GBP","beneficiary":{"type":"external_account","account_holder_name":"Test","account_identifier":{"type":"sort_code_account_number","sort_code":"121212","account_number":"12345678"},"reference":"Test reference"},"status":"authorized","created_at":"2022-04-01T19:47:12.642232Z","authorized_at":"2022-04-01T19:47:12.642232Z"}');
    }

    public static function executed(): Response
    {
        return new Response(200, [], '{"id":"cbc98b01-6d4e-4137-a6fc-1659a26e5c55","merchant_account_id":"822f8dfe-0874-481d-b966-5b14f767792f","amount_in_minor":1,"currency":"GBP","beneficiary":{"type":"external_account","account_holder_name":"Test","account_identifier":{"type":"sort_code_account_number","sort_code":"121212","account_number":"12345678"},"reference":"Test reference"},"status":"executed","created_at":"2022-04-01T19:47:12.642232Z","authorized_at":"2022-04-01T19:47:12.642232Z","executed_at":"2022-04-01T19:47:13.642232Z"}');
    }

    public static function executed_with_metadata(): Response
    {
        return new Response(200, [], '{"id":"cbc98b01-6d4e-4137-a6fc-1659a26e5c55","merchant_account_id":"822f8dfe-0874-481d-b966-5b14f767792f","amount_in_minor":1,"currency":"GBP","beneficiary":{"type":"external_account","account_holder_name":"Test","account_identifier":{"type":"sort_code_account_number","sort_code":"121212","account_number":"12345678"},"reference":"Test reference"},"status":"executed","created_at":"2022-04-01T19:47:12.642232Z","authorized_at":"2022-04-01T19:47:12.642232Z","executed_at":"2022-04-01T19:47:13.642232Z", "metadata": {"foo": "bar"}}');
    }

    public static function failed(): Response
    {
        return new Response(200, [], '{"id":"cbc98b01-6d4e-4137-a6fc-1659a26e5c55","merchant_account_id":"822f8dfe-0874-481d-b966-5b14f767792f","amount_in_minor":1,"currency":"GBP","beneficiary":{"type":"external_account","account_holder_name":"Test","account_identifier":{"type":"sort_code_account_number","sort_code":"121212","account_number":"12345678"},"reference":"Test reference"},"status":"failed","created_at":"2022-04-01T19:47:12.642232Z","authorized_at":"2022-04-01T19:47:12.642232Z","failed_at":"2022-04-01T19:48:14.317504Z"}');
    }

    public static function pendingWithPaymentSource(): Response
    {
        return new Response(200, [], '{"id":"cbc98b01-6d4e-4137-a6fc-1659a26e5c55","merchant_account_id":"822f8dfe-0874-481d-b966-5b14f767792f","amount_in_minor":1,"currency":"GBP","beneficiary":{"type":"payment_source","payment_source_id":"source1","user_id":"user1","reference":"Test reference"},"status":"pending","created_at":"2022-04-01T19:47:12.642232Z"}');
    }

    public static function created(): Response
    {
        return new Response(200, [], '{"id":"ca9a3154-9151-44cf-b7cb-073c9e12ef91"}');
    }
}
