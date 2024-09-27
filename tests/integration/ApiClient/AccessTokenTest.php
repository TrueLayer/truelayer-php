<?php

declare(strict_types=1);

use Carbon\Carbon;
use GuzzleHttp\Psr7\Response;
use TrueLayer\Tests\Integration\Mocks;

\it('appends access token', function () {
    \request()->post();
    $auth = \getSentHttpRequests()[1]->getHeaderLine('Authorization');
    \expect($auth)->toBe('Bearer ' . Mocks\AuthResponse::ACCESS_TOKEN);
});

\it('reuses access token across requests', function () {
    $okResponse = new Response(200);
    $client = \rawClient([Mocks\AuthResponse::success(), $okResponse, $okResponse])
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

    $client = \rawClient([
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
    $client = \rawClient([
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

\it('reuses cached access token across requests',
    function () {
        $okResponse = new Response(200);
        $encrypter = new TrueLayer\Services\Util\Encryption\Encrypter(\hex2bin('31c8d81a110849f83131541b9f67c3cba9c7e0bb103bc4dd19377f0fdf2d924b'), TrueLayer\Constants\Encryption::ALGORITHM);

        $cacheMock = Mockery::mock(Psr\SimpleCache\CacheInterface::class);
        $cacheMock->shouldReceive('has')->andReturnTrue();
        $cacheMock->shouldReceive('set')->andReturnTrue();
        $cacheMock->shouldReceive('get')->andReturn($encrypter->encrypt([
            'access_token' => Mocks\AuthResponse::ACCESS_TOKEN,
            'expires_in' => 3600,
            'retrieved_at' => (int) Carbon::now()->timestamp,
        ]));
        $client1 = \rawClient([Mocks\AuthResponse::success(), $okResponse, $okResponse])
            ->cache($cacheMock, '31c8d81a110849f83131541b9f67c3cba9c7e0bb103bc4dd19377f0fdf2d924b')
            ->create()
            ->getApiClient();
        $client2 = \rawClient([$okResponse, $okResponse])
            ->cache($cacheMock, '31c8d81a110849f83131541b9f67c3cba9c7e0bb103bc4dd19377f0fdf2d924b')
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
    $encrypter = new TrueLayer\Services\Util\Encryption\Encrypter(\hex2bin('31c8d81a110849f83131541b9f67c3cba9c7e0bb103bc4dd19377f0fdf2d924b'), TrueLayer\Constants\Encryption::ALGORITHM);

    $cacheMock = Mockery::mock(Psr\SimpleCache\CacheInterface::class);
    $cacheMock->shouldReceive('has')->andReturnTrue();
    $cacheMock->shouldReceive('set')->andReturnTrue();
    $cacheMock->shouldReceive('get')->andReturn($encrypter->encrypt([
        'access_token' => 'expired-token',
        'expires_in' => 3600,
        'retrieved_at' => (int) Carbon::now()->timestamp - 5000,
    ]));

    $client = \rawClient([Mocks\AuthResponse::success(), $okResponse, $okResponse])
        ->cache($cacheMock, '31c8d81a110849f83131541b9f67c3cba9c7e0bb103bc4dd19377f0fdf2d924b')
        ->create()
        ->getApiClient();

    $client->request()->uri('/foo')->post();
    $fooRequest = \getSentHttpRequests()[1];

    \expect($fooRequest->getHeaderLine('Authorization'))->toBe('Bearer ' . Mocks\AuthResponse::ACCESS_TOKEN);
    \expect($fooRequest->getHeaderLine('Authorization'))->not()->toBe('Bearer expired-token');
});

\it('uses different cache key if client id is changed', function () {
    $okResponse = new Response(200);
    $encrypter = new TrueLayer\Services\Util\Encryption\Encrypter(\hex2bin('31c8d81a110849f83131541b9f67c3cba9c7e0bb103bc4dd19377f0fdf2d924b'), TrueLayer\Constants\Encryption::ALGORITHM);
    $encryptedAccessToken = $encrypter->encrypt([
        'access_token' => Mocks\AuthResponse::ACCESS_TOKEN,
        'expires_in' => 3600,
        'retrieved_at' => (int) Carbon::now()->timestamp,
    ]);

    $cacheMock1 = Mockery::mock(Psr\SimpleCache\CacheInterface::class);
    $cacheMock1->shouldReceive('has')->andReturnTrue();
    $cacheMock1->shouldReceive('set')->andReturnTrue();
    $cacheMock1->shouldReceive('get')->andReturn($encryptedAccessToken);

    $client1 = \rawClient([Mocks\AuthResponse::success(), $okResponse, $okResponse])
        ->cache($cacheMock1, '31c8d81a110849f83131541b9f67c3cba9c7e0bb103bc4dd19377f0fdf2d924b')
        ->clientId('client_id_1')
        ->create()
        ->getApiClient();
    $client1->request()->uri('/foo')->post();

    $cacheKey1 = null;
    $cacheDefaultValue = 'not-null';

    $cacheMock1->shouldHaveReceived('get', function (...$args) use (&$cacheKey1, &$cacheDefaultValue) {
        $cacheKey1 = $args[0];
        $cacheDefaultValue = $args[1];
        return true;
    });
    \expect($cacheKey1)->toBeString();
    \expect($cacheKey1)->not()->toBeEmpty();
    \expect($cacheDefaultValue)->toBeNull();

    $cacheMock2 = Mockery::mock(Psr\SimpleCache\CacheInterface::class);
    $cacheMock2->shouldReceive('has')->andReturnTrue();
    $cacheMock2->shouldReceive('set')->andReturnTrue();
    $cacheMock2->shouldReceive('get')->andReturn($encryptedAccessToken);

    $client2 = \rawClient([Mocks\AuthResponse::success(), $okResponse, $okResponse])
        ->cache($cacheMock2, '31c8d81a110849f83131541b9f67c3cba9c7e0bb103bc4dd19377f0fdf2d924b')
        ->clientId('client_id_2')
        ->create()
        ->getApiClient();
    $client2->request()->uri('/foo')->post();

    $cacheMock2->shouldNotHaveReceived('get', [$cacheKey1, $cacheDefaultValue]);
});

\it('uses different cache key if client secret is changed', function () {
    $okResponse = new Response(200);
    $encrypter = new TrueLayer\Services\Util\Encryption\Encrypter(\hex2bin('31c8d81a110849f83131541b9f67c3cba9c7e0bb103bc4dd19377f0fdf2d924b'), TrueLayer\Constants\Encryption::ALGORITHM);
    $encryptedAccessToken = $encrypter->encrypt([
        'access_token' => Mocks\AuthResponse::ACCESS_TOKEN,
        'expires_in' => 3600,
        'retrieved_at' => (int) Carbon::now()->timestamp,
    ]);

    $cacheMock1 = Mockery::mock(Psr\SimpleCache\CacheInterface::class);
    $cacheMock1->shouldReceive('has')->andReturnTrue();
    $cacheMock1->shouldReceive('set')->andReturnTrue();
    $cacheMock1->shouldReceive('get')->andReturn($encryptedAccessToken);

    $client1 = \rawClient([Mocks\AuthResponse::success(), $okResponse, $okResponse])
        ->cache($cacheMock1, '31c8d81a110849f83131541b9f67c3cba9c7e0bb103bc4dd19377f0fdf2d924b')
        ->clientSecret('client_secret_1')
        ->create()
        ->getApiClient();
    $client1->request()->uri('/foo')->post();

    $cacheKey1 = null;
    $cacheDefaultValue = 'not-null';

    $cacheMock1->shouldHaveReceived('get', function (...$args) use (&$cacheKey1, &$cacheDefaultValue) {
        $cacheKey1 = $args[0];
        $cacheDefaultValue = $args[1];
        return true;
    });
    \expect($cacheKey1)->toBeString();
    \expect($cacheKey1)->not()->toBeEmpty();
    \expect($cacheDefaultValue)->toBeNull();

    $cacheMock2 = Mockery::mock(Psr\SimpleCache\CacheInterface::class);
    $cacheMock2->shouldReceive('has')->andReturnTrue();
    $cacheMock2->shouldReceive('set')->andReturnTrue();
    $cacheMock2->shouldReceive('get')->andReturn($encryptedAccessToken);

    $client2 = \rawClient([Mocks\AuthResponse::success(), $okResponse, $okResponse])
        ->cache($cacheMock2, '31c8d81a110849f83131541b9f67c3cba9c7e0bb103bc4dd19377f0fdf2d924b')
        ->clientSecret('client_secret_2')
        ->create()
        ->getApiClient();
    $client2->request()->uri('/foo')->post();

    $cacheMock2->shouldNotHaveReceived('get', [$cacheKey1, $cacheDefaultValue]);
});

\it('uses different cache key if scopes are changed', function () {
    $okResponse = new Response(200);
    $encrypter = new TrueLayer\Services\Util\Encryption\Encrypter(\hex2bin('31c8d81a110849f83131541b9f67c3cba9c7e0bb103bc4dd19377f0fdf2d924b'), TrueLayer\Constants\Encryption::ALGORITHM);
    $encryptedAccessToken = $encrypter->encrypt([
        'access_token' => Mocks\AuthResponse::ACCESS_TOKEN,
        'expires_in' => 3600,
        'retrieved_at' => (int) Carbon::now()->timestamp,
    ]);

    $cacheMock1 = Mockery::mock(Psr\SimpleCache\CacheInterface::class);
    $cacheMock1->shouldReceive('has')->andReturnTrue();
    $cacheMock1->shouldReceive('set')->andReturnTrue();
    $cacheMock1->shouldReceive('get')->andReturn($encryptedAccessToken);

    $client1 = \rawClient([Mocks\AuthResponse::success(), $okResponse, $okResponse])
        ->cache($cacheMock1, '31c8d81a110849f83131541b9f67c3cba9c7e0bb103bc4dd19377f0fdf2d924b')
        ->scopes('payments')
        ->create()
        ->getApiClient();
    $client1->request()->uri('/foo')->post();

    $cacheKey1 = null;
    $cacheDefaultValue = 'not-null';

    $cacheMock1->shouldHaveReceived('get', function (...$args) use (&$cacheKey1, &$cacheDefaultValue) {
        $cacheKey1 = $args[0];
        $cacheDefaultValue = $args[1];
        return true;
    });
    \expect($cacheKey1)->toBeString();
    \expect($cacheKey1)->not()->toBeEmpty();
    \expect($cacheDefaultValue)->toBeNull();

    $cacheMock2 = Mockery::mock(Psr\SimpleCache\CacheInterface::class);
    $cacheMock2->shouldReceive('has')->andReturnTrue();
    $cacheMock2->shouldReceive('set')->andReturnTrue();
    $cacheMock2->shouldReceive('get')->andReturn($encryptedAccessToken);

    $client2 = \rawClient([Mocks\AuthResponse::success(), $okResponse, $okResponse])
        ->cache($cacheMock2, '31c8d81a110849f83131541b9f67c3cba9c7e0bb103bc4dd19377f0fdf2d924b')
        ->scopes('payments', 'accounts')
        ->create()
        ->getApiClient();
    $client2->request()->uri('/foo')->post();

    $cacheMock2->shouldNotHaveReceived('get', [$cacheKey1, $cacheDefaultValue]);
});

\it('uses default scope if none provided', function () {
    $client = \rawClient([Mocks\AuthResponse::success(), new Response(200)])->create();
    $client->getApiClient()->request()->uri('/test')->post();

    $requestedScope = \getRequestPayload(0)['scope'];
    \expect($requestedScope)->toBe(TrueLayer\Constants\Scopes::DEFAULT);
});

\it('uses custom scopes if provided', function () {
    $client = \rawClient([Mocks\AuthResponse::success(), new Response(200)])
        ->scopes('foo', 'bar')
        ->create();

    $client->getApiClient()->request()->uri('/test')->post();

    $requestedScope = \getRequestPayload(0)['scope'];
    \expect($requestedScope)->toBe('foo bar');
});
