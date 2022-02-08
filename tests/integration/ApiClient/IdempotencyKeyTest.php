<?php

declare(strict_types=1);

use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Collection;
use TrueLayer\Constants\CustomHeaders;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Tests\Integration\Mocks\ErrorResponse;

\it('appends idempotency key', function () {
    \request()->post();
    \expect(\getSentHttpRequests()[1]->getHeaderLine(CustomHeaders::IDEMPOTENCY_KEY))->not()->toBeNull();
});

\it('retries requests with same idempotency key', function () {
    $responses = [
        ErrorResponse::providerError(),
        ErrorResponse::idempotencyKeyConcurrencyConflict(),
        ErrorResponse::conflict(),
        new Response(200),
    ];

    \request($responses)->post();

    $sentRequests = Collection::make(\getSentHttpRequests());
    $sentRequests->shift(); // Remove access token request

    $idempotencyKeys = $sentRequests
        ->map(fn ($r) => $r->getHeaderLine(CustomHeaders::IDEMPOTENCY_KEY))
        ->unique()
        ->count();

    \expect($idempotencyKeys)->toBe(1);
});

\it('regenerates idempotency key on reuse error', function () {
    \request([ErrorResponse::idempotencyKeyReuse(), new Response(200)])->post();

    $sentRequests = Collection::make(\getSentHttpRequests());
    $sentRequests->shift(); // Remove access token request

    $idempotencyKeys = $sentRequests
        ->map(fn ($r) => $r->getHeaderLine(CustomHeaders::IDEMPOTENCY_KEY))
        ->unique()
        ->count();

    \expect($idempotencyKeys)->toBe(2);
});

\it('regenerates idempotency key only once', function () {
    $error = ErrorResponse::idempotencyKeyReuse();
    \request([$error, $error, $error])->post();
})->throws(ApiResponseUnsuccessfulException::class);
