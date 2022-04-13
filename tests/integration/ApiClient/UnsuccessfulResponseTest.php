<?php

declare(strict_types=1);

use TrueLayer\Exceptions;
use TrueLayer\Tests\Integration\Mocks\ErrorResponse;

\it('handles non-200 responses', function () {
    \request(ErrorResponse::forbidden())->post();
})->throws(Exceptions\ApiResponseUnsuccessfulException::class);

\it('handles invalid parameters response', function () {
    try {
        \request(ErrorResponse::invalidParameters())->post();
    } catch (Exceptions\ApiResponseUnsuccessfulException $e) {
        \expect($e->getMessage())->toBe('Invalid Parameters');
        \expect($e->getErrors())->toHaveKey('beneficiary.type');

        throw $e;
    }
})->throws(Exceptions\ApiResponseUnsuccessfulException::class);

\it('handles legacy error format', function () {
    try {
        \request(ErrorResponse::legacyErrorFormat())->post();
    } catch (Exceptions\ApiResponseUnsuccessfulException $e) {
        \expect($e->getMessage())->toBe('invalid_client');
        \expect($e->getTitle())->toBe('invalid_client');
        \expect($e->getTraceId())->toBe('123');
        \expect($e->getStatusCode())->toBe(400);
        \expect($e->getType())->toBe('https://docs.truelayer.com/docs/error-types');

        throw $e;
    }
})->throws(Exceptions\ApiResponseUnsuccessfulException::class);

\it('handles errors with no body', function () {
    try {
        \request(ErrorResponse::noBodyErrorFormat())->post();
    } catch (Exceptions\ApiResponseUnsuccessfulException $e) {
        \expect($e->getMessage())->toBe('server_error');
        \expect($e->getTitle())->toBe('server_error');
        \expect($e->getTraceId())->toBe('123');
        \expect($e->getStatusCode())->toBe(400);
        \expect($e->getType())->toBe('https://docs.truelayer.com/docs/error-types');

        throw $e;
    }
})->throws(Exceptions\ApiResponseUnsuccessfulException::class);
