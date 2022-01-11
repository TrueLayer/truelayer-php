<?php

declare(strict_types=1);

use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Carbon;
use TrueLayer\Tests\Mocks;

\it('appends access token', function () {
    \request()->post();
    $auth = \getSentHttpRequests()[1]->getHeaderLine('Authorization');
    \expect($auth)->toBe('Bearer ' . Mocks\AuthResponse::ACCESS_TOKEN);
});

\it('reuses access token across requests', function () {
    $okResponse = new Response(200);
    $client = \rawSdk([Mocks\AuthResponse::success(), $okResponse, $okResponse])
        ->create()
        ->getApiClient();

    $client->request()->uri('/foo')->post();
    $client->request()->uri('/bar')->post();

    $fooRequest = \getSentHttpRequests()[1];
    $barRequest = \getSentHttpRequests()[2];

    \expect($fooRequest->getHeaderLine('Authorization'))->toBe('Bearer ' . Mocks\AuthResponse::ACCESS_TOKEN);
    \expect($barRequest->getHeaderLine('Authorization'))->toBe('Bearer ' . Mocks\AuthResponse::ACCESS_TOKEN);
});

\it('fetches new token on expiry', function () {
    $okResponse = new Response(200);

    $client = \rawSdk([
        Mocks\AuthResponse::success(),
        $okResponse,
        Mocks\AuthResponse::success('_SECOND_'),
        $okResponse,
    ])
        ->create()
        ->getApiClient();

    $client->request()->uri('/foo')->post();

    $expiryDate = Carbon::now()->addSeconds(3600);
    Carbon::setTestNow($expiryDate);

    $client->request()->uri('/bar')->post();

    $fooRequest = \getSentHttpRequests()[1];
    $barRequest = \getSentHttpRequests()[3];

    \expect($fooRequest->getUri()->getPath())->toBe('/foo');
    \expect($fooRequest->getHeaderLine('Authorization'))->toBe('Bearer ' . Mocks\AuthResponse::ACCESS_TOKEN);
    \expect($barRequest->getUri()->getPath())->toBe('/bar');
    \expect($barRequest->getHeaderLine('Authorization'))->toBe('Bearer ' . Mocks\AuthResponse::ACCESS_TOKEN . '_SECOND_');
});

\it('fetches new token on unauthenticated error response', function () {
    $client = \rawSdk([
        Mocks\AuthResponse::success(), // Retrieve the access token
        Mocks\ErrorResponse::unauthenticated(), // Reject the access token in the api call
        Mocks\AuthResponse::success('_SECOND_'), // Retrieve new access token
        new Response(200), ] // Accept the access token in the api call
    )
    ->create();

    $client->getApiClient()->request()->uri('/foo')->post();

    $firstAccessToken = \getSentHttpRequests()[1]->getHeaderLine('Authorization');
    $secondAccessToken = \getSentHttpRequests()[3]->getHeaderLine('Authorization');

    \expect($firstAccessToken)->toBe('Bearer ' . Mocks\AuthResponse::ACCESS_TOKEN);
    \expect($secondAccessToken)->toBe('Bearer ' . Mocks\AuthResponse::ACCESS_TOKEN . '_SECOND_');
});

\it('reuses cached access token across requests', function () {
    $okResponse = new Response(200);

    $cacheMock = Mockery::mock(\Psr\SimpleCache\CacheInterface::class);
    $cacheMock->shouldReceive('has')->andReturnTrue();
    $cacheMock->shouldReceive('set')->andReturnTrue();
    $cacheMock->shouldReceive('get')->andReturn(serialize([
        'access_token' => Mocks\AuthResponse::ACCESS_TOKEN,
        'expires_in' => 3600,
        'retrieved_at' => (int) Carbon::now()->timestamp,
    ]));

    $client1 = \rawSdk([Mocks\AuthResponse::success(), $okResponse, $okResponse])
        ->cache($cacheMock)
        ->create()
        ->getApiClient();
    $client2 = \rawSdk([$okResponse, $okResponse])
        ->cache($cacheMock)
        ->create()
        ->getApiClient();

    $client1->request()->uri('/foo')->post();
    $fooRequest = \getSentHttpRequests()[0];

    $client2->request()->uri('/bar')->post();
    $barRequest = \getSentHttpRequests()[0];

    \expect($fooRequest->getHeaderLine('Authorization'))->toBe('Bearer ' . Mocks\AuthResponse::ACCESS_TOKEN);
    \expect($barRequest->getHeaderLine('Authorization'))->toBe('Bearer ' . Mocks\AuthResponse::ACCESS_TOKEN);
});

\it('fetches a new token if the cached one is expired', function () {
    $okResponse = new Response(200);

    $cacheMock = Mockery::mock(\Psr\SimpleCache\CacheInterface::class);
    $cacheMock->shouldReceive('has')->andReturnTrue();
    $cacheMock->shouldReceive('set')->andReturnTrue();
    $cacheMock->shouldReceive('get')->andReturn(serialize([
        'access_token' => 'expired-token',
        'expires_in' => 3600,
        'retrieved_at' => (int) Carbon::now()->timestamp - 5000,
    ]));

    $client = \rawSdk([Mocks\AuthResponse::success(), $okResponse, $okResponse])
        ->cache($cacheMock)
        ->create()
        ->getApiClient();

    $client->request()->uri('/foo')->post();
    $fooRequest = \getSentHttpRequests()[1];

    \expect($fooRequest->getHeaderLine('Authorization'))->toBe('Bearer ' . Mocks\AuthResponse::ACCESS_TOKEN);
    \expect($fooRequest->getHeaderLine('Authorization'))->not()->toBe('Bearer expired-token');
});
