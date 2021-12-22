<?php

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use TrueLayer\Sdk;
use TrueLayer\Tests\Mocks\AuthResponse;

$httpTransactions = [];

/**
 * @param $mockResponses The responses returned by the 'server'
 * @return \TrueLayer\Contracts\Sdk\SdkInterface
 */
function sdk($mockResponses = [])
{
    global $httpTransactions;

    $httpTransactions = [];

    // For the first http call, we return the Auth token
    // For subsequent calls we return the given response(s)
    $responses = array_merge(
        [AuthResponse::success()],
        is_array($mockResponses) ? $mockResponses : [$mockResponses]
    );

    $handlerStack = HandlerStack::create(new MockHandler($responses));

    $handlerStack->push(\GuzzleHttp\Middleware::history($httpTransactions));

    $mockClient = new Client([ 'handler' => $handlerStack ]);

    return Sdk::configure()
        ->clientId('client_id')
        ->clientSecret('client_secret')
        ->keyId('123')
        ->pemFile(__DIR__.'/Mocks/ec512-private.pem')
        ->httpClient($mockClient)
        ->create();
}

/**
 * @param array $mockResponses
 * @return \TrueLayer\Contracts\Api\ApiRequestInterface
 */
function request($mockResponses = []): \TrueLayer\Contracts\Api\ApiRequestInterface
{
    if (empty($mockResponses)) {
        $mockResponses = new \GuzzleHttp\Psr7\Response(200, [], 'OK');
    }

    return sdk($mockResponses)->getApiClient()->request()
        ->uri('/test');
}

/**
 * @return \Psr\Http\Message\ServerRequestInterface[]
 */
function getSentHttpRequests(): array
{
    global $httpTransactions;

    return \Illuminate\Support\Arr::pluck(
        $httpTransactions,
        'request'
    );
}
