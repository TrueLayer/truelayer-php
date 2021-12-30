<?php

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
    $client = \rawSdk([Mocks\AuthResponse::success(), $okResponse, $okResponse])->getApiClient();

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
    ])->getApiClient();

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
    );

    $client->getApiClient()->request()->uri('/foo')->post();

    $firstAccessToken = \getSentHttpRequests()[1]->getHeaderLine('Authorization');
    $secondAccessToken = \getSentHttpRequests()[3]->getHeaderLine('Authorization');

    \expect($firstAccessToken)->toBe('Bearer ' . Mocks\AuthResponse::ACCESS_TOKEN);
    \expect($secondAccessToken)->toBe('Bearer ' . Mocks\AuthResponse::ACCESS_TOKEN . '_SECOND_');
});
