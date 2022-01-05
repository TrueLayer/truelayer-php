<?php

declare(strict_types=1);

namespace TrueLayer\Tests\Mocks;

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

    public static function retrieved(): Response
    {
    }
}
