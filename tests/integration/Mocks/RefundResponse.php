<?php

declare(strict_types=1);

namespace TrueLayer\Tests\Integration\Mocks;

use GuzzleHttp\Psr7\Response;

class RefundResponse
{
    public static function created(): Response
    {
        return new Response(200, [], '{"id":"56bbff85-9504-4cba-a63b-c781745ad3ed"}');
    }

    public static function pending(): Response
    {
        return new Response(200, [], '{"id":"56bbff85-9504-4cba-a63b-c781745ad3ed","amount_in_minor":1,"currency":"GBP","reference":"TEST","created_at":"2022-02-04T13:40:23.798415Z","status":"pending"}');
    }

    public static function authorized(): Response
    {
        return new Response(200, [], '{"id":"56bbff85-9504-4cba-a63b-c781745ad3ed","amount_in_minor":1,"currency":"GBP","reference":"TEST","created_at":"2022-02-04T13:40:23.798415Z","status":"authorized"}');
    }

    public static function executed(): Response
    {
        return new Response(200, [], '{"id":"56bbff85-9504-4cba-a63b-c781745ad3ed","amount_in_minor":1,"currency":"GBP","reference":"TEST","created_at":"2022-02-04T13:40:23.798415Z","status":"executed","executed_at":"2022-02-04T14:12:07.705938Z"}');
    }

    public static function executedWithMetadata(): Response
    {
        return new Response(200, [], '{"id":"56bbff85-9504-4cba-a63b-c781745ad3ed","amount_in_minor":1,"currency":"GBP","reference":"TEST","created_at":"2022-02-04T13:40:23.798415Z","status":"executed","executed_at":"2022-02-04T14:12:07.705938Z", "metadata": {"foo": "bar"}}');
    }

    public static function failed(): Response
    {
        return new Response(200, [], '{"id":"56bbff85-9504-4cba-a63b-c781745ad3ed","amount_in_minor":1,"currency":"GBP","reference":"TEST","created_at":"2022-02-04T13:40:23.798415Z","status":"failed","failed_at":"2022-02-06T22:26:48.849469Z","failure_reason":"authorization_failed"}');
    }

    public static function all(): Response
    {
        $items = \implode(',', [
            self::pending()->getBody()->getContents(),
            self::authorized()->getBody()->getContents(),
            self::executed()->getBody()->getContents(),
            self::failed()->getBody()->getContents(),
        ]);

        return new Response(200, [], "{\"items\":[{$items}]}");
    }
}
