<?php

declare(strict_types=1);

namespace TrueLayer\Tests\Mocks;

use GuzzleHttp\Psr7\Response;

class PaymentResponse
{
    public const CREATED = [
        'id' => '5a2a0a0d-d3ad-4740-860b-45a01bcc17ac',
        'payment_token' => 'the-token',
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
        return new Response(200, [], '{"id":"3bb10530-28c6-4b77-8d07-904110a4a17c","amount_in_minor":1,"currency":"GBP","beneficiary":{"type":"merchant_account","name":"Test","id":"82b98c1a-8dd9-49c2-b23b-666457e415b2"},"user":{"id":"706934f7-95df-4224-9a7d-f6f48b05009e","name":"Alex","email":"aaaa@a.com"},"payment_method":{"type":"bank_transfer","statement_reference":"Statement ref"},"created_at":"2022-01-13T20:13:07.991551Z","status":"authorization_required"}');
    }

    public static function authorizingProviderSelection(): Response
    {
        return new Response(200, [], '{"id":"3bb10530-28c6-4b77-8d07-904110a4a17c","amount_in_minor":1,"currency":"GBP","beneficiary":{"type":"merchant_account","name":"Test","id":"82b98c1a-8dd9-49c2-b23b-666457e415b2"},"user":{"id":"706934f7-95df-4224-9a7d-f6f48b05009e","name":"Alex","email":"aaaa@a.com"},"payment_method":{"type":"bank_transfer","statement_reference":"Statement ref"},"created_at":"2022-01-13T20:13:07.991551Z","status":"authorizing","authorization_flow":{"actions":{"next":{"type":"provider_selection","providers":[{"provider_id":"mock-payments-gb-redirect","display_name":"Mock UK Payments - Redirect Flow","icon_uri":"https://truelayer-provider-assets.s3.amazonaws.com/uk/icons/mock-payments-gb-redirect.svg","logo_uri":"https://truelayer-provider-assets.s3.amazonaws.com/uk/logos/mock-payments-gb-redirect.svg","bg_color":"#FFFFFF","country_code":"GB"},{"provider_id":"oauth-starling","display_name":"Starling","icon_uri":"https://truelayer-client-logos.s3-eu-west-1.amazonaws.com/banks/banks-icons/oauth-starling-icon.svg","logo_uri":"https://truelayer-client-logos.s3-eu-west-1.amazonaws.com/banks/oauth-starling.svg","bg_color":"#007EB6","country_code":"GB"},{"provider_id":"ob-barclays","display_name":"Barclays","icon_uri":"https://truelayer-provider-assets.s3.amazonaws.com/global/icons/barclays.svg","logo_uri":"https://truelayer-provider-assets.s3.amazonaws.com/global/logos/barclays.svg","bg_color":"#007EB6","country_code":"GB"},{"provider_id":"ob-boi","display_name":"Bank of Ireland UK","icon_uri":"https://truelayer-provider-assets.s3.amazonaws.com/global/icons/boi.svg","logo_uri":"https://truelayer-provider-assets.s3.amazonaws.com/global/logos/boi.svg","bg_color":"#125B84","country_code":"GB"},{"provider_id":"ob-bos","display_name":"Bank of Scotland","icon_uri":"https://truelayer-provider-assets.s3.amazonaws.com/uk/icons/bos.svg","logo_uri":"https://truelayer-provider-assets.s3.amazonaws.com/uk/logos/bos.svg","bg_color":"#05286a","country_code":"GB"},{"provider_id":"ob-danske","display_name":"Danske Bank","icon_uri":"https://truelayer-provider-assets.s3.amazonaws.com/global/icons/danske.svg","logo_uri":"https://truelayer-provider-assets.s3.amazonaws.com/global/logos/danske.svg","bg_color":"#003755","country_code":"GB"},{"provider_id":"ob-first-direct","display_name":"first direct","icon_uri":"https://truelayer-provider-assets.s3.amazonaws.com/uk/icons/first-direct.svg","logo_uri":"https://truelayer-provider-assets.s3.amazonaws.com/uk/logos/first-direct.svg","bg_color":"#626268","country_code":"GB"},{"provider_id":"ob-halifax","display_name":"Halifax","icon_uri":"https://truelayer-provider-assets.s3.amazonaws.com/uk/icons/halifax.svg","logo_uri":"https://truelayer-provider-assets.s3.amazonaws.com/uk/logos/halifax.svg","bg_color":"#0040bb","country_code":"GB"},{"provider_id":"ob-hsbc","display_name":"HSBC","icon_uri":"https://truelayer-provider-assets.s3.amazonaws.com/global/icons/hsbc.svg","logo_uri":"https://truelayer-provider-assets.s3.amazonaws.com/global/logos/hsbc.svg","bg_color":"#515358","country_code":"GB"},{"provider_id":"ob-lloyds","display_name":"Lloyds","icon_uri":"https://truelayer-provider-assets.s3.amazonaws.com/global/icons/lloyds.svg","logo_uri":"https://truelayer-provider-assets.s3.amazonaws.com/global/logos/lloyds.svg","bg_color":"#00553e","country_code":"GB"},{"provider_id":"ob-monzo","display_name":"Monzo","icon_uri":"https://truelayer-provider-assets.s3.amazonaws.com/global/icons/monzo.svg","logo_uri":"https://truelayer-provider-assets.s3.amazonaws.com/global/logos/monzo.svg","bg_color":"#15233c","country_code":"GB"},{"provider_id":"ob-nationwide","display_name":"Nationwide","icon_uri":"https://truelayer-provider-assets.s3.amazonaws.com/uk/icons/nationwide.svg","logo_uri":"https://truelayer-provider-assets.s3.amazonaws.com/uk/logos/nationwide.svg","bg_color":"#002878","country_code":"GB"},{"provider_id":"ob-natwest","display_name":"NatWest","icon_uri":"https://truelayer-provider-assets.s3.amazonaws.com/global/icons/natwest.svg","logo_uri":"https://truelayer-provider-assets.s3.amazonaws.com/global/logos/natwest.svg","bg_color":"#42145f","country_code":"GB"},{"provider_id":"ob-rbs","display_name":"Royal Bank of Scotland","icon_uri":"https://truelayer-provider-assets.s3.amazonaws.com/global/icons/rbs.svg","logo_uri":"https://truelayer-provider-assets.s3.amazonaws.com/global/logos/rbs.svg","bg_color":"#0A2F64","country_code":"GB"},{"provider_id":"ob-revolut","display_name":"Revolut","icon_uri":"https://truelayer-provider-assets.s3.amazonaws.com/global/icons/revolut.svg","logo_uri":"https://truelayer-provider-assets.s3.amazonaws.com/global/logos/revolut.svg","bg_color":"#0067EA","country_code":"GB"},{"provider_id":"ob-santander","display_name":"Santander","icon_uri":"https://truelayer-provider-assets.s3.amazonaws.com/global/icons/santander.svg","logo_uri":"https://truelayer-provider-assets.s3.amazonaws.com/global/logos/santander.svg","bg_color":"#EC0000","country_code":"GB"},{"provider_id":"ob-tesco","display_name":"Tesco Bank","icon_uri":"https://truelayer-provider-assets.s3.amazonaws.com/uk/icons/tesco.svg","logo_uri":"https://truelayer-provider-assets.s3.amazonaws.com/uk/logos/tesco.svg","bg_color":"#1b3160","country_code":"GB"},{"provider_id":"ob-tsb","display_name":"TSB","icon_uri":"https://truelayer-provider-assets.s3.amazonaws.com/uk/icons/tsb.svg","logo_uri":"https://truelayer-provider-assets.s3.amazonaws.com/uk/logos/tsb.svg","bg_color":"#007BC3","country_code":"GB"},{"provider_id":"ob-uki-mock-bank","display_name":"UKI Mock Bank","icon_uri":"https://truelayer-provider-assets.s3.amazonaws.com/global/icons/generic.svg","logo_uri":"https://truelayer-provider-assets.s3.amazonaws.com/global/logos/generic.svg","bg_color":"","country_code":"GB"},{"provider_id":"ob-ulster","display_name":"Ulster Bank","icon_uri":"https://truelayer-provider-assets.s3.amazonaws.com/global/icons/ulster.svg","logo_uri":"https://truelayer-provider-assets.s3.amazonaws.com/global/logos/ulster.svg","bg_color":"#0a2f64","country_code":"GB"}]}},"configuration":{"provider_selection":{"status":"supported"},"redirect":{"status":"supported","return_uri":"https://penny.t7r.dev/redirect/v3"}}}}');
    }

    public static function authorizingRedirect(): Response
    {
        return new Response(200, [], '{"id":"3bb10530-28c6-4b77-8d07-904110a4a17c","amount_in_minor":1,"currency":"GBP","beneficiary":{"type":"merchant_account","name":"Test","id":"82b98c1a-8dd9-49c2-b23b-666457e415b2"},"user":{"id":"706934f7-95df-4224-9a7d-f6f48b05009e","name":"Alex","email":"aaaa@a.com"},"payment_method":{"type":"bank_transfer","statement_reference":"Statement ref"},"created_at":"2022-01-13T20:13:07.991551Z","status":"authorizing","authorization_flow":{"actions":{"next":{"type":"redirect","uri":"https://foo.com"}},"configuration":{"provider_selection":{"status":"supported"},"redirect":{"status":"supported","return_uri":"https://penny.t7r.dev/redirect/v3"}}}}');
    }

    public static function authorizingWait(): Response
    {
        return new Response(200, [], '{"id":"3bb10530-28c6-4b77-8d07-904110a4a17c","amount_in_minor":1,"currency":"GBP","beneficiary":{"type":"merchant_account","name":"Test","id":"82b98c1a-8dd9-49c2-b23b-666457e415b2"},"user":{"id":"706934f7-95df-4224-9a7d-f6f48b05009e","name":"Alex","email":"aaaa@a.com"},"payment_method":{"type":"bank_transfer","statement_reference":"Statement ref"},"created_at":"2022-01-13T20:13:07.991551Z","status":"authorizing","authorization_flow":{"actions":{"next":{"type":"wait"}},"configuration":{"provider_selection":{"status":"supported"},"redirect":{"status":"supported","return_uri":"https://penny.t7r.dev/redirect/v3"}}}}');
    }

    public static function authorized(): Response
    {
        return new Response(200, [], '{"id":"3bb10530-28c6-4b77-8d07-904110a4a17c","amount_in_minor":1,"currency":"GBP","beneficiary":{"type":"merchant_account","name":"Test","id":"82b98c1a-8dd9-49c2-b23b-666457e415b2"},"user":{"id":"706934f7-95df-4224-9a7d-f6f48b05009e","name":"Alex","email":"aaaa@a.com"},"payment_method":{"type":"bank_transfer","statement_reference":"Statement ref"},"created_at":"2022-01-13T20:13:07.991551Z","status":"authorized","authorization_flow":{"configuration":{"provider_selection":{"status":"supported"},"redirect":{"status":"supported","return_uri":"https://penny.t7r.dev/redirect/v3"}}}}');
    }

    public static function executed(): Response
    {
        return new Response(200, [], '{"id":"6a582c5f-ec17-443c-9edb-141e9f8ab1ce","amount_in_minor":1,"currency":"GBP","beneficiary":{"type":"merchant_account","name":"Test","id":"82b98c1a-8dd9-49c2-b23b-666457e415b2"},"user":{"id":"2ac7b0af-a329-4eff-a3db-10a4d7263d24","name":"Alex","email":"aaaa@a.com"},"payment_method":{"type":"bank_transfer","statement_reference":"Statement ref"},"created_at":"2022-01-13T22:12:48.935404Z","status":"executed","authorization_flow":{"configuration":{"provider_selection":{"status":"supported"},"redirect":{"status":"supported","return_uri":"https://penny.t7r.dev/redirect/v3"}}},"executed_at":"2022-01-13T22:13:09.914177Z","source_of_funds":{"type":"external_account","scheme_identifiers":[]}}');
    }

    public static function settled(): Response
    {
        return new Response(200, [], '{"id":"6a582c5f-ec17-443c-9edb-141e9f8ab1ce","amount_in_minor":1,"currency":"GBP","beneficiary":{"type":"merchant_account","name":"Test","id":"82b98c1a-8dd9-49c2-b23b-666457e415b2"},"user":{"id":"2ac7b0af-a329-4eff-a3db-10a4d7263d24","name":"Alex","email":"aaaa@a.com"},"payment_method":{"type":"bank_transfer","statement_reference":"Statement ref"},"created_at":"2022-01-13T22:12:48.935404Z","status":"settled","authorization_flow":{"configuration":{"provider_selection":{"status":"supported"},"redirect":{"status":"supported","return_uri":"https://penny.t7r.dev/redirect/v3"}}},"executed_at":"2022-01-13T22:13:09.914177Z","settled_at":"2022-01-13T22:13:09.914177Z","source_of_funds":{"type":"external_account","scheme_identifiers":[]}}');
    }

    public static function failed(): Response
    {
        return new Response(200, [], '{"id":"3bb10530-28c6-4b77-8d07-904110a4a17c","amount_in_minor":1,"currency":"GBP","beneficiary":{"type":"merchant_account","name":"Test","id":"82b98c1a-8dd9-49c2-b23b-666457e415b2"},"user":{"id":"706934f7-95df-4224-9a7d-f6f48b05009e","name":"Alex","email":"aaaa@a.com"},"payment_method":{"type":"bank_transfer","statement_reference":"Statement ref"},"created_at":"2022-01-13T20:13:07.991551Z","status":"failed","authorization_flow":{"configuration":{"provider_selection":{"status":"supported"},"redirect":{"status":"supported","return_uri":"https://penny.t7r.dev/redirect/v3"}}},"failed_at":"2022-01-13T20:22:25.645589Z","failure_stage":"authorizing","failure_reason":"authorization_failed"}');
    }
}
