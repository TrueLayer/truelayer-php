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
}
