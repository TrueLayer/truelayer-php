<?php

declare(strict_types=1);

namespace TrueLayer\Tests\Integration\Mocks;

use GuzzleHttp\Psr7\Response;

class StartAuthorizationFlowResponse
{
    /**
     * @return Response
     */
    public static function success(): Response
    {
        return new Response(200, [], '{"status":"authorizing","authorization_flow":{"actions":{"next":{"type":"redirect","uri":"https://pay-mock-connect.t7r.dev/login/a7d5f4a5-f2d7-464a-af26-22f6f417d0e9#token=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJqdGkiOiJhN2Q1ZjRhNS1mMmQ3LTQ2NGEtYWYyNi0yMmY2ZjQxN2QwZTkiLCJzY29wZSI6InBheS1tb2NrLWNvbm5lY3QtYXBpIiwibmJmIjoxNjQzOTgzNzM3LCJleHAiOjE2NDM5ODczMzcsImlzcyI6Imh0dHBzOi8vcGF5LW1vY2stY29ubmVjdC50N3IuZGV2IiwiYXVkIjoiaHR0cHM6Ly9wYXktbW9jay1jb25uZWN0LnQ3ci5kZXYifQ.l_qafIgtWJGxZcsQNOeASYa9xeKij2GBbbKBpQKET98"}}}}');
    }

    public static function failed(): Response
    {
        return new Response(200, [], '{"status":"failed","failure_stage":"authorizing","failure_reason":"provider_rejected"}');
    }
}
