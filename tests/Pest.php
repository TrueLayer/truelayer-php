<?php

declare(strict_types=1);

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use TrueLayer\Sdk;
use TrueLayer\Services\Util\Retry;
use TrueLayer\Tests\Mocks\AuthResponse;

$httpTransactions = [];
$sleeps = [];

Retry::$testSleeper = function (int $microseconds) use ($sleeps) {
    $sleeps[] = $microseconds;
};

\uses()
    ->beforeEach(function () {
        global $sleeps;
        $sleeps = [];
    })
    ->in(__DIR__);

/**
 * Get an instance of the SDK with mocked http client.
 *
 * @param array $mockResponses The responses returned by the 'server'
 *
 * @return \TrueLayer\Interfaces\Sdk\SdkConfigInterface
 *@throws \TrueLayer\Exceptions\ApiRequestValidationException
 * @throws \TrueLayer\Exceptions\ApiResponseUnsuccessfulException
 * @throws \TrueLayer\Exceptions\ApiResponseValidationException
 * @throws \TrueLayer\Exceptions\InvalidArgumentException
 *
 * @throws \TrueLayer\Exceptions\ApiRequestJsonSerializationException
 */
function rawSdk(array $mockResponses = [])
{
    global $httpTransactions;

    $httpTransactions = [];

    $handlerStack = HandlerStack::create(new MockHandler($mockResponses));

    $handlerStack->push(\GuzzleHttp\Middleware::history($httpTransactions));

    $mockClient = new Client(['handler' => $handlerStack]);

    return Sdk::configure()
        ->clientId('client_id')
        ->clientSecret('client_secret')
        ->keyId('123')
        ->pemFile(__DIR__ . '/Mocks/ec512-private.pem')
        ->httpClient($mockClient);
}

/**
 * Get an instance of the SDK with mocked http client and access token call.
 *
 * @param $mockResponses The responses returned by the 'server'
 *
 * @return \TrueLayer\Interfaces\Sdk\SdkInterface
 */
function sdk($mockResponses = [])
{
    // For the first http call, we return the Auth token
    // For subsequent calls we return the given response(s)
    $responses = \array_merge(
        [AuthResponse::success()],
        \is_array($mockResponses) ? $mockResponses : [$mockResponses]
    );

    return \rawSdk($responses)->create();
}

/**
 * Create a new request with a mocked successful response.
 *
 * @param array $mockResponses
 *
 * @return \TrueLayer\Interfaces\ApiClient\ApiRequestInterface
 */
function request($mockResponses = []): TrueLayer\Interfaces\ApiClient\ApiRequestInterface
{
    if (empty($mockResponses)) {
        $mockResponses = new \GuzzleHttp\Psr7\Response(200, [], 'OK');
    }

    return \sdk($mockResponses)->getApiClient()->request()
        ->uri('/test');
}

/**
 * @return \Psr\Http\Message\ServerRequestInterface[]
 */
function getSentHttpRequests(): array
{
    global $httpTransactions;

    return \Illuminate\Support\Arr::pluck($httpTransactions, 'request');
}

/**
 * @param int $requestIndex
 *
 * @return mixed
 */
function getRequestPayload(int $requestIndex)
{
    return \json_decode(\getSentHttpRequests()[$requestIndex]->getBody()->getContents(), true);
}
