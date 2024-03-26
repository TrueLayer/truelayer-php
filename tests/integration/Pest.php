<?php

declare(strict_types=1);

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use TrueLayer\Constants\CustomHeaders;
use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\SignerException;
use TrueLayer\Exceptions\WebhookHandlerInvalidArgumentException;
use TrueLayer\Interfaces\Webhook\WebhookInterface;
use TrueLayer\Services\Util\Retry;
use TrueLayer\Tests\Integration\Mocks\AuthResponse;
use TrueLayer\Tests\Integration\Mocks\Signing;

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
 * @return TrueLayer\Interfaces\Configuration\ClientConfigInterface
 * @throws SignerException
 *
 */
function rawClient(array $mockResponses = [])
{
    global $httpTransactions;

    $httpTransactions = [];

    $handlerStack = HandlerStack::create(new MockHandler($mockResponses));

    $handlerStack->push(GuzzleHttp\Middleware::history($httpTransactions));

    $mockClient = new HttpClient(['handler' => $handlerStack]);

    return TrueLayer\Client::configure()
        ->clientId('client_id')
        ->clientSecret('client_secret')
        ->keyId('123')
        ->pemFile(__DIR__ . '/Mocks/ec512-private.pem')
        ->httpClient($mockClient);
}

/**
 * Get an instance of the SDK with mocked http client and access token call.
 *
 * @param array $mockResponses The responses returned by the 'server'
 *
 * @return TrueLayer\Interfaces\Client\ClientInterface
 * @throws SignerException
 *
 */
function client($mockResponses = [])
{
    // For the first http call, we return the Auth token
    // For subsequent calls we return the given response(s)
    $responses = \array_merge(
        [AuthResponse::success()],
        \is_array($mockResponses) ? $mockResponses : [$mockResponses]
    );

    return \rawClient($responses)->create();
}

/**
 * Create a new request with a mocked successful response.
 *
 * @param array $mockResponses
 *
 * @throws SignerException
 */
function request($mockResponses = []): TrueLayer\Interfaces\ApiClient\ApiRequestInterface
{
    if (empty($mockResponses)) {
        $mockResponses = new GuzzleHttp\Psr7\Response(200, [], 'OK');
    }

    return \client($mockResponses)->getApiClient()->request()
        ->uri('/test');
}

/**
 * @return \Psr\Http\Message\ServerRequestInterface[]
 */
function getSentHttpRequests(): array
{
    global $httpTransactions;

    return array_map(fn($transaction) => $transaction['request'], $httpTransactions);
}

/**
 * @param int $requestIndex
 * @param bool $asArray
 *
 * @return mixed
 */
function getRequestPayload(int $requestIndex, bool $asArray = true)
{
    $body = \getSentHttpRequests()[$requestIndex]->getBody();
    $body->rewind();

    return $asArray
        ? \json_decode($body->getContents(), true)
        : $body->getContents();
}

/**
 * @param int $requestIndex
 * @param string $name
 *
 * @return string[]
 */
function getRequestHeader(int $requestIndex, string $name): array
{
    return \getSentHttpRequests()[$requestIndex]->getHeader($name);
}

/**
 * @param int $requestIndex
 *
 * @return string
 */
function getRequestIdempotencyKey(int $requestIndex): string
{
    return \getRequestHeader($requestIndex, CustomHeaders::IDEMPOTENCY_KEY)[0];
}

/**
 * @param string $body
 *
 * @return WebhookInterface
 * @throws WebhookHandlerInvalidArgumentException
 * @throws TrueLayer\Signing\Exceptions\InvalidArgumentException
 * @throws ApiRequestJsonSerializationException
 * @throws ApiResponseUnsuccessfulException
 * @throws TrueLayer\Exceptions\InvalidArgumentException
 *
 * @throws SignerException
 */
function webhook(string $body): WebhookInterface
{
    return \rawClient([Signing::getPublicKeysResponse()])->create()
        ->webhook()
        ->body($body)
        ->path(Signing::getPath())
        ->headers(Signing::getHeaders($body));
}
