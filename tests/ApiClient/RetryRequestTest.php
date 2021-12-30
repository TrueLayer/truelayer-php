<?php

use GuzzleHttp\Psr7\Response;
use TrueLayer\Exceptions;
use TrueLayer\Services\Api\Decorators\ExponentialBackoffDecorator;

\it('retries requests until successful', function () {
    $serverErrorResponse = new Response(500, [], '');
    $okResponse = new Response(200, [], '');
    $responses = [$serverErrorResponse, $serverErrorResponse, $okResponse];

    \request($responses)->post();
    $sentRequests = \getSentHttpRequests();

    // 1 access token + 2 server errors + 1 success
    \expect(\count($sentRequests))->toBe(4);
});

\it('retries request up to max limit', function () {
    $maxRetries = ExponentialBackoffDecorator::MAX_RETRIES;
    $responses = \array_fill(0, $maxRetries * 2, new Response(500, [], ''));

    try {
        \request($responses)->post();
    } catch (Exceptions\ApiResponseUnsuccessfulException $e) {
        // Total requests are access token request + api call + max_retries
        \expect(\count(\getSentHttpRequests()))->toBe($maxRetries + 1 + 1);

        throw $e;
    }
})->expectException(Exceptions\ApiResponseUnsuccessfulException::class);
