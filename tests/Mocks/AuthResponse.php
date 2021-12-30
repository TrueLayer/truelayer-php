<?php

declare(strict_types=1);

namespace TrueLayer\Tests\Mocks;

use GuzzleHttp\Psr7\Response;

class AuthResponse
{
    public const ACCESS_TOKEN = 'JWT-ACCESS-TOKEN-HERE';

    /**
     * @param string $append
     * @return Response
     */
    public static function success(string $append = ''): Response
    {
        $data = [
            'access_token' => self::ACCESS_TOKEN . $append,
            'expires_in' => 3600,
            'token_type' => 'Bearer',
        ];

        return new Response(200, [], \json_encode($data));
    }
}
