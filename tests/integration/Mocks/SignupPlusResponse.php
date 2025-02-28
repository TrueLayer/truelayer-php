<?php

declare(strict_types=1);

namespace TrueLayer\Tests\Integration\Mocks;

use GuzzleHttp\Psr7\Response;

class SignupPlusResponse
{
    public static function authUriCreated(): Response
    {
        return new Response(201, [], '{"auth_uri":"https://demo-api.gii.cloud/api/oauth/auth_proxy?id=863619242079485953&uuid=b912cc0d-149b-40a2-8a24-79a9d1f0913e"}');
    }

    public static function userDataRetrievedFinland(): Response
    {
        return new Response(200, [], '{"first_name": "Tero Testi","last_name": "Äyrämö","date_of_birth": "1970-01-01","national_identification_number": "010170-1234","sex": "M","address": {"address_line1": "Kauppa Puistikko 6 B 15","city": "VAASA","zip": "65100","country": "FI"},"account_details": {"iban": "FI53CLRB04066200002723","provider_id": "fi-op"}}');
    }

    public static function userDataRetrievedUk(): Response
    {
        return new Response(200, [], '{"title": "Mr","first_name": "Sherlock","last_name": "Holmes","date_of_birth": "1854-01-06","address": {"address_line1": "221B Baker St","address_line2": "Flat 2","city": "London","state": "Greater London","zip": "NW1 6XE","country": "GB"},"account_details": {"account_number": "41921234","sort_code": "04-01-02","iban": "GB71MONZ04435141923452","provider_id": "ob-monzo"}}');
    }
}
