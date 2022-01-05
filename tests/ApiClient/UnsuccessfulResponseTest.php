<?php

declare(strict_types=1);

use TrueLayer\Exceptions;
use TrueLayer\Tests\Mocks\ErrorResponse;

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
