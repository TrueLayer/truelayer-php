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
}
