<?php

declare(strict_types=1);

namespace TrueLayer\Tests\Integration\Mocks;

use GuzzleHttp\Psr7\Response;

class PaymentResponse
{
    public const CREATED = [
        'id' => '5a2a0a0d-d3ad-4740-860b-45a01bcc17ac',
        'resource_token' => 'the-token',
        'user' => [
            'id' => 'ba6412a3-8b2c-4d33-a40b-9964062979da',
        ],
    ];

    /**
     * @return Response
     */
    public static function created(): Response
    {
        return new Response(200, [], \json_encode(self::CREATED));
    }

    public static function authorizationRequired(): Response
    {
        return new Response(200, [], '{"id":"56bbff85-9504-4cba-a63b-c781745ad3ed","amount_in_minor":1,"metadata":{"metadata_key_1":"metadata_value_1","metadata_key_2":"metadata_value_2","metadata_key_3":"metadata_value_3"},"currency":"GBP","user":{"id":"c4e754fd-8b0d-40fe-bd61-36622b7477a4"},"payment_method":{"type":"bank_transfer","beneficiary":{"type":"external_account","account_identifier":{"type":"sort_code_account_number","sort_code":"010203","account_number":"12345678"},"account_holder_name":"Bob","reference":"TEST"},"provider_selection":{"type":"user_selected"}},"created_at":"2022-02-04T13:40:23.798415Z","status":"authorization_required"}');
    }

    public static function authorizationRequiredWithRetryField(): Response
    {
        return new Response(200, [], '{"id":"56bbff85-9504-4cba-a63b-c781745ad3ed","amount_in_minor":1,"metadata":{"metadata_key_1":"metadata_value_1","metadata_key_2":"metadata_value_2","metadata_key_3":"metadata_value_3"},"currency":"GBP","user":{"id":"c4e754fd-8b0d-40fe-bd61-36622b7477a4"},"payment_method":{"type":"bank_transfer","retry":{},"beneficiary":{"type":"external_account","account_identifier":{"type":"sort_code_account_number","sort_code":"010203","account_number":"12345678"},"account_holder_name":"Bob","reference":"TEST"},"provider_selection":{"type":"user_selected"}},"created_at":"2022-02-04T13:40:23.798415Z","status":"authorization_required"}');
    }

    public static function authorizingProviderSelection(): Response
    {
        return new Response(200, [], '{"id":"56bbff85-9504-4cba-a63b-c781745ad3ed","amount_in_minor":1,"metadata":{"metadata_key_1":"metadata_value_1","metadata_key_2":"metadata_value_2","metadata_key_3":"metadata_value_3"},"currency":"GBP","user":{"id":"c4e754fd-8b0d-40fe-bd61-36622b7477a4"},"payment_method":{"type":"bank_transfer","beneficiary":{"type":"external_account","account_identifier":{"type":"sort_code_account_number","sort_code":"010203","account_number":"12345678"},"account_holder_name":"Bob","reference":"TEST"},"provider_selection":{"type":"user_selected"}},"created_at":"2022-02-04T13:40:23.798415Z","status":"authorizing","authorization_flow":{"actions":{"next":{"type":"provider_selection","providers":[{"id":"mock-payments-gb-redirect","display_name":"Mock UK Payments - Redirect Flow","icon_uri":"https://truelayer-provider-assets.s3.amazonaws.com/uk/icons/mock-payments-gb-redirect.svg","logo_uri":"https://truelayer-provider-assets.s3.amazonaws.com/uk/logos/mock-payments-gb-redirect.svg","bg_color":"#FFFFFF","country_code":"GB"},{"id":"oauth-starling","display_name":"Starling","icon_uri":"https://truelayer-client-logos.s3-eu-west-1.amazonaws.com/banks/banks-icons/oauth-starling-icon.svg","logo_uri":"https://truelayer-client-logos.s3-eu-west-1.amazonaws.com/banks/oauth-starling.svg","bg_color":"#007EB6","country_code":"GB"},{"id":"ob-barclays","display_name":"Barclays","icon_uri":"https://truelayer-provider-assets.s3.amazonaws.com/global/icons/barclays.svg","logo_uri":"https://truelayer-provider-assets.s3.amazonaws.com/global/logos/barclays.svg","bg_color":"#007EB6","country_code":"GB"},{"id":"ob-boi","display_name":"Bank of Ireland UK","icon_uri":"https://truelayer-provider-assets.s3.amazonaws.com/global/icons/boi.svg","logo_uri":"https://truelayer-provider-assets.s3.amazonaws.com/global/logos/boi.svg","bg_color":"#125B84","country_code":"GB"},{"id":"ob-bos","display_name":"Bank of Scotland","icon_uri":"https://truelayer-provider-assets.s3.amazonaws.com/uk/icons/bos.svg","logo_uri":"https://truelayer-provider-assets.s3.amazonaws.com/uk/logos/bos.svg","bg_color":"#05286a","country_code":"GB"},{"id":"ob-danske","display_name":"Danske Bank","icon_uri":"https://truelayer-provider-assets.s3.amazonaws.com/global/icons/danske.svg","logo_uri":"https://truelayer-provider-assets.s3.amazonaws.com/global/logos/danske.svg","bg_color":"#003755","country_code":"GB"},{"id":"ob-first-direct","display_name":"first direct","icon_uri":"https://truelayer-provider-assets.s3.amazonaws.com/uk/icons/first-direct.svg","logo_uri":"https://truelayer-provider-assets.s3.amazonaws.com/uk/logos/first-direct.svg","bg_color":"#626268","country_code":"GB"},{"id":"ob-halifax","display_name":"Halifax","icon_uri":"https://truelayer-provider-assets.s3.amazonaws.com/uk/icons/halifax.svg","logo_uri":"https://truelayer-provider-assets.s3.amazonaws.com/uk/logos/halifax.svg","bg_color":"#0040bb","country_code":"GB"},{"id":"ob-hsbc","display_name":"HSBC","icon_uri":"https://truelayer-provider-assets.s3.amazonaws.com/global/icons/hsbc.svg","logo_uri":"https://truelayer-provider-assets.s3.amazonaws.com/global/logos/hsbc.svg","bg_color":"#515358","country_code":"GB"},{"id":"ob-lloyds","display_name":"Lloyds","icon_uri":"https://truelayer-provider-assets.s3.amazonaws.com/global/icons/lloyds.svg","logo_uri":"https://truelayer-provider-assets.s3.amazonaws.com/global/logos/lloyds.svg","bg_color":"#00553e","country_code":"GB"},{"id":"ob-monzo","display_name":"Monzo","icon_uri":"https://truelayer-provider-assets.s3.amazonaws.com/global/icons/monzo.svg","logo_uri":"https://truelayer-provider-assets.s3.amazonaws.com/global/logos/monzo.svg","bg_color":"#15233c","country_code":"GB"},{"id":"ob-nationwide","display_name":"Nationwide","icon_uri":"https://truelayer-provider-assets.s3.amazonaws.com/uk/icons/nationwide.svg","logo_uri":"https://truelayer-provider-assets.s3.amazonaws.com/uk/logos/nationwide.svg","bg_color":"#002878","country_code":"GB"},{"id":"ob-natwest","display_name":"NatWest","icon_uri":"https://truelayer-provider-assets.s3.amazonaws.com/global/icons/natwest.svg","logo_uri":"https://truelayer-provider-assets.s3.amazonaws.com/global/logos/natwest.svg","bg_color":"#42145f","country_code":"GB"},{"id":"ob-rbs","display_name":"Royal Bank of Scotland","icon_uri":"https://truelayer-provider-assets.s3.amazonaws.com/global/icons/rbs.svg","logo_uri":"https://truelayer-provider-assets.s3.amazonaws.com/global/logos/rbs.svg","bg_color":"#0A2F64","country_code":"GB"},{"id":"ob-revolut","display_name":"Revolut","icon_uri":"https://truelayer-provider-assets.s3.amazonaws.com/global/icons/revolut.svg","logo_uri":"https://truelayer-provider-assets.s3.amazonaws.com/global/logos/revolut.svg","bg_color":"#0067EA","country_code":"GB"},{"id":"ob-santander","display_name":"Santander","icon_uri":"https://truelayer-provider-assets.s3.amazonaws.com/global/icons/santander.svg","logo_uri":"https://truelayer-provider-assets.s3.amazonaws.com/global/logos/santander.svg","bg_color":"#EC0000","country_code":"GB"},{"id":"ob-tesco","display_name":"Tesco Bank","icon_uri":"https://truelayer-provider-assets.s3.amazonaws.com/uk/icons/tesco.svg","logo_uri":"https://truelayer-provider-assets.s3.amazonaws.com/uk/logos/tesco.svg","bg_color":"#1b3160","country_code":"GB"},{"id":"ob-tsb","display_name":"TSB","icon_uri":"https://truelayer-provider-assets.s3.amazonaws.com/uk/icons/tsb.svg","logo_uri":"https://truelayer-provider-assets.s3.amazonaws.com/uk/logos/tsb.svg","bg_color":"#007BC3","country_code":"GB"},{"id":"ob-uki-mock-bank","display_name":"UKI Mock Bank","icon_uri":"https://truelayer-provider-assets.s3.amazonaws.com/global/icons/generic.svg","logo_uri":"https://truelayer-provider-assets.s3.amazonaws.com/global/logos/generic.svg","bg_color":"","country_code":"GB"},{"id":"ob-ulster","display_name":"Ulster Bank","icon_uri":"https://truelayer-provider-assets.s3.amazonaws.com/global/icons/ulster.svg","logo_uri":"https://truelayer-provider-assets.s3.amazonaws.com/global/logos/ulster.svg","bg_color":"#0a2f64","country_code":"GB"}]}},"configuration":{"provider_selection":{},"redirect":{"return_uri":"https://penny.t7r.dev/redirect/v3"}}}}');
    }

    public static function authorizingRedirect(): Response
    {
        return new Response(200, [], '{"id":"56bbff85-9504-4cba-a63b-c781745ad3ed","amount_in_minor":1,"metadata":{"metadata_key_1":"metadata_value_1","metadata_key_2":"metadata_value_2","metadata_key_3":"metadata_value_3"},"currency":"GBP","user":{"id":"c4e754fd-8b0d-40fe-bd61-36622b7477a4"},"payment_method":{"type":"bank_transfer","beneficiary":{"type":"external_account","account_identifier":{"type":"sort_code_account_number","sort_code":"010203","account_number":"12345678"},"account_holder_name":"Bob","reference":"TEST"},"provider_selection":{"type":"user_selected"}},"created_at":"2022-02-04T13:40:23.798415Z","status":"authorizing","authorization_flow":{"actions":{"next":{"type":"redirect","uri":"https://pay-mock-connect.t7r.dev/login/a7d5f4a5-f2d7-464a-af26-22f6f417d0e9#token=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJqdGkiOiJhN2Q1ZjRhNS1mMmQ3LTQ2NGEtYWYyNi0yMmY2ZjQxN2QwZTkiLCJzY29wZSI6InBheS1tb2NrLWNvbm5lY3QtYXBpIiwibmJmIjoxNjQzOTgzNzM3LCJleHAiOjE2NDM5ODczMzcsImlzcyI6Imh0dHBzOi8vcGF5LW1vY2stY29ubmVjdC50N3IuZGV2IiwiYXVkIjoiaHR0cHM6Ly9wYXktbW9jay1jb25uZWN0LnQ3ci5kZXYifQ.l_qafIgtWJGxZcsQNOeASYa9xeKij2GBbbKBpQKET98"}},"configuration":{"provider_selection":{},"redirect":{"return_uri":"https://penny.t7r.dev/redirect/v3"}}}}');
    }

    public static function authorizingWait(): Response
    {
        return new Response(200, [], '{"id":"56bbff85-9504-4cba-a63b-c781745ad3ed","amount_in_minor":1,"metadata":{"metadata_key_1":"metadata_value_1","metadata_key_2":"metadata_value_2","metadata_key_3":"metadata_value_3"},"currency":"GBP","user":{"id":"c4e754fd-8b0d-40fe-bd61-36622b7477a4"},"payment_method":{"type":"bank_transfer","beneficiary":{"type":"external_account","account_identifier":{"type":"sort_code_account_number","sort_code":"010203","account_number":"12345678"},"account_holder_name":"Bob","reference":"TEST"},"provider_selection":{"type":"user_selected"}},"created_at":"2022-02-04T13:40:23.798415Z","status":"authorizing","authorization_flow":{"actions":{"next":{"type":"wait"}},"configuration":{"provider_selection":{},"redirect":{"return_uri":"https://penny.t7r.dev/redirect/v3"}}}}');
    }

    public static function authorized(): Response
    {
        return new Response(200, [], '{"id":"56bbff85-9504-4cba-a63b-c781745ad3ed","amount_in_minor":1,"metadata":{"metadata_key_1":"metadata_value_1","metadata_key_2":"metadata_value_2","metadata_key_3":"metadata_value_3"},"currency":"GBP","user":{"id":"c4e754fd-8b0d-40fe-bd61-36622b7477a4"},"payment_method":{"type":"bank_transfer","beneficiary":{"type":"external_account","account_identifier":{"type":"sort_code_account_number","sort_code":"010203","account_number":"12345678"},"account_holder_name":"Bob","reference":"TEST"},"provider_selection":{"type":"user_selected"}},"created_at":"2022-02-04T13:40:23.798415Z","status":"authorized","authorization_flow":{"configuration":{"provider_selection":{},"redirect":{"return_uri":"https://penny.t7r.dev/redirect/v3"}}}}');
    }

    public static function executed(): Response
    {
        return new Response(200, [], '{"id":"56bbff85-9504-4cba-a63b-c781745ad3ed","amount_in_minor":1,"metadata":{"metadata_key_1":"metadata_value_1","metadata_key_2":"metadata_value_2","metadata_key_3":"metadata_value_3"},"currency":"GBP","user":{"id":"c4e754fd-8b0d-40fe-bd61-36622b7477a4"},"payment_method":{"type":"bank_transfer","beneficiary":{"type":"external_account","account_identifier":{"type":"sort_code_account_number","sort_code":"010203","account_number":"12345678"},"account_holder_name":"Bob","reference":"TEST"},"provider_selection":{"type":"user_selected"}},"created_at":"2022-02-04T13:40:23.798415Z","status":"executed","authorization_flow":{"configuration":{"provider_selection":{},"redirect":{"return_uri":"https://penny.t7r.dev/redirect/v3"}}},"executed_at":"2022-02-04T14:12:07.705938Z"}');
    }

    public static function executedNoAuthFlow(): Response
    {
        return new Response(200, [], '{"id":"56bbff85-9504-4cba-a63b-c781745ad3ed","amount_in_minor":1,"metadata":{"metadata_key_1":"metadata_value_1","metadata_key_2":"metadata_value_2","metadata_key_3":"metadata_value_3"},"currency":"GBP","user":{"id":"c4e754fd-8b0d-40fe-bd61-36622b7477a4"},"payment_method":{"type":"bank_transfer","beneficiary":{"type":"external_account","account_identifier":{"type":"sort_code_account_number","sort_code":"010203","account_number":"12345678"},"account_holder_name":"Bob","reference":"TEST"},"provider_selection":{"type":"user_selected"}},"created_at":"2022-02-04T13:40:23.798415Z","status":"executed","authorization_flow":null,"executed_at":"2022-02-04T14:12:07.705938Z"}');
    }

    public static function executedWithUserSelectedSchemeSelection(): Response
    {
        return new Response(200, [], '{"id":"56bbff85-9504-4cba-a63b-c781745ad3ed","amount_in_minor":1,"metadata":{"metadata_key_1":"metadata_value_1","metadata_key_2":"metadata_value_2","metadata_key_3":"metadata_value_3"},"currency":"GBP","user":{"id":"c4e754fd-8b0d-40fe-bd61-36622b7477a4"},"payment_method":{"type":"bank_transfer","beneficiary":{"type":"external_account","account_identifier":{"type":"sort_code_account_number","sort_code":"010203","account_number":"12345678"},"account_holder_name":"Bob","reference":"TEST"},"provider_selection":{"type":"user_selected","scheme_selection":{"type":"user_selected"}}},"created_at":"2022-02-04T13:40:23.798415Z","status":"executed","authorization_flow":{"configuration":{"provider_selection":{},"redirect":{"return_uri":"https://penny.t7r.dev/redirect/v3"}}},"executed_at":"2022-02-04T14:12:07.705938Z"}');
    }

    public static function executedWithInstantOnlySchemeSelection(): Response
    {
        return new Response(200, [], '{"id":"56bbff85-9504-4cba-a63b-c781745ad3ed","amount_in_minor":1,"metadata":{"metadata_key_1":"metadata_value_1","metadata_key_2":"metadata_value_2","metadata_key_3":"metadata_value_3"},"currency":"GBP","user":{"id":"c4e754fd-8b0d-40fe-bd61-36622b7477a4"},"payment_method":{"type":"bank_transfer","beneficiary":{"type":"external_account","account_identifier":{"type":"sort_code_account_number","sort_code":"010203","account_number":"12345678"},"account_holder_name":"Bob","reference":"TEST"},"provider_selection":{"type":"user_selected","scheme_selection":{"type":"instant_only","allow_remitter_fee":true}}},"created_at":"2022-02-04T13:40:23.798415Z","status":"executed","authorization_flow":{"configuration":{"provider_selection":{},"redirect":{"return_uri":"https://penny.t7r.dev/redirect/v3"}}},"executed_at":"2022-02-04T14:12:07.705938Z"}');
    }

    public static function executedWithInstantPreferredSchemeSelection(): Response
    {
        return new Response(200, [], '{"id":"56bbff85-9504-4cba-a63b-c781745ad3ed","amount_in_minor":1,"metadata":{"metadata_key_1":"metadata_value_1","metadata_key_2":"metadata_value_2","metadata_key_3":"metadata_value_3"},"currency":"GBP","user":{"id":"c4e754fd-8b0d-40fe-bd61-36622b7477a4"},"payment_method":{"type":"bank_transfer","beneficiary":{"type":"external_account","account_identifier":{"type":"sort_code_account_number","sort_code":"010203","account_number":"12345678"},"account_holder_name":"Bob","reference":"TEST"},"provider_selection":{"type":"user_selected","scheme_selection":{"type":"instant_preferred","allow_remitter_fee":true}}},"created_at":"2022-02-04T13:40:23.798415Z","status":"executed","authorization_flow":{"configuration":{"provider_selection":{},"redirect":{"return_uri":"https://penny.t7r.dev/redirect/v3"}}},"executed_at":"2022-02-04T14:12:07.705938Z"}');
    }

    public static function settled(): Response
    {
        return new Response(200, [], '{"id":"b9a7d0e9-4de9-425a-b282-cf2a4f998c5d","amount_in_minor":1,"metadata":{"metadata_key_1":"metadata_value_1","metadata_key_2":"metadata_value_2","metadata_key_3":"metadata_value_3"},"currency":"GBP","user":{"id":"277329bd-184c-40ef-878f-e2209e1ce522"},"payment_method":{"type":"bank_transfer","beneficiary":{"type":"merchant_account", "reference": "TEST", "merchant_account_id":"822f8dfe-0874-481d-b966-5b14f767792f"},"provider_selection":{"type":"user_selected"}},"created_at":"2022-02-06T22:13:25.299154Z","status":"settled","authorization_flow":{"configuration":{"provider_selection":{},"redirect":{"return_uri":"https://penny.t7r.dev/redirect/v3"}}},"executed_at":"2022-02-06T22:14:48.014149Z","payment_source":{"account_identifiers":[{"type":"sort_code_account_number","sort_code":"040662","account_number":"00002723"},{"type":"iban","iban":"GB53CLRB04066200002723"},{"type":"bban","bban":"CLRB04066200002723"},{"type":"nrb","nrb":"61109010140000071219812874"}],"id":"3c928d0c-c76f-4062-b370-b10f3070b89d","account_holder_name":"Bob"},"settled_at":"2022-02-06T22:14:51.382114Z"}');
    }

    public static function settledNoPaymentSourceFields(): Response
    {
        return new Response(200, [], '{"id":"b9a7d0e9-4de9-425a-b282-cf2a4f998c5d","amount_in_minor":1,"metadata":{"metadata_key_1":"metadata_value_1","metadata_key_2":"metadata_value_2","metadata_key_3":"metadata_value_3"},"currency":"GBP","user":{"id":"277329bd-184c-40ef-878f-e2209e1ce522"},"payment_method":{"type":"bank_transfer","beneficiary":{"type":"merchant_account", "reference": "TEST", "merchant_account_id":"822f8dfe-0874-481d-b966-5b14f767792f"},"provider_selection":{"type":"user_selected"}},"created_at":"2022-02-06T22:13:25.299154Z","status":"settled","authorization_flow":{"configuration":{"provider_selection":{},"redirect":{"return_uri":"https://penny.t7r.dev/redirect/v3"}}},"executed_at":"2022-02-06T22:14:48.014149Z","payment_source":{},"settled_at":"2022-02-06T22:14:51.382114Z"}');
    }

    public static function failed(): Response
    {
        return new Response(200, [], '{"id":"401cfaa1-8d44-4306-a2f9-a0a6e365f570","amount_in_minor":1,"metadata":{"metadata_key_1":"metadata_value_1","metadata_key_2":"metadata_value_2","metadata_key_3":"metadata_value_3"},"currency":"GBP","user":{"id":"7ed73602-c8bc-4b2f-8a96-9490a6ea5983"},"payment_method":{"type":"bank_transfer","beneficiary":{"type":"external_account","account_identifier":{"type":"sort_code_account_number","sort_code":"010203","account_number":"12345678"},"account_holder_name":"Bob","reference":"TEST"},"provider_selection":{"type":"user_selected"}},"created_at":"2022-02-06T22:25:43.899669Z","status":"failed","authorization_flow":{"configuration":{"provider_selection":{},"redirect":{"return_uri":"https://penny.t7r.dev/redirect/v3"}}},"failed_at":"2022-02-06T22:26:48.849469Z","failure_stage":"authorizing","failure_reason":"authorization_failed"}');
    }

}
