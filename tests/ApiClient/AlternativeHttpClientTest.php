<?php

declare(strict_types=1);

use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Psr18Client;
use Symfony\Component\HttpClient\Response\MockResponse;
use TrueLayer\Sdk;
use TrueLayer\Tests\Mocks\AuthResponse;

\it('accepts a custom PSR client', function () {
    $responses = [
        new MockResponse(AuthResponse::success()->getBody()->getContents()),
        new MockResponse('{"test": "value", "username":"alex"}'),
    ];

    $client = new Psr18Client(new MockHttpClient($responses));

    $sdk = Sdk::configure()
        ->clientId('client_id')
        ->clientSecret('client_secret')
        ->keyId('123')
        ->pemFile(__DIR__ . '/../Mocks/ec512-private.pem')
        ->httpClient($client)
        ->create();

    $result = $sdk->getApiClient()->request()->uri('/bar')->post();

    \expect($result['test'])->toBe('value');
    \expect($result['username'])->toBe('alex');
});
