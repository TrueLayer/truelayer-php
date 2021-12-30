<?php

declare(strict_types=1);

use GuzzleHttp\Psr7\Response;
use TrueLayer\Exceptions;
use TrueLayer\Tests\Mocks\ErrorResponse;

\it('validates request payload', function () {
    $request = \request()
        ->payload([
            'param' => 123,
            'valid' => 'test',
            'nested' => ['param' => 456],
        ])
        ->requestRules(fn () => [
            'param' => 'required|email',
            'valid' => 'required|string',
            'nested.param' => 'required|string',
        ]);

    try {
        $request->post();
    } catch (Exceptions\ApiRequestValidationException $e) {
        \expect($e->getMessage())->toContain('invalid');
        \expect($e->getErrors())->toHaveKeys(['param', 'nested.param']);
        \expect($e->getErrors())->not()->toHaveKey('valid');
        throw $e;
    }
})->throws(Exceptions\ApiRequestValidationException::class);

\it('validates response payload', function () {
    $body = '{"test": "value", "username":"alex"}';

    try {
        \request(new Response(200, [], $body))
            ->responseRules(fn () => [
                'test' => 'string',
                'username' => 'email',
            ])
            ->post();
    } catch (Exceptions\ApiResponseValidationException $e) {
        \expect($e->getErrors())->toHaveKey('username');
        \expect($e->getErrors())->not()->toHaveKey('test');
        throw ($e);
    }
})->throws(Exceptions\ApiResponseValidationException::class);

\it('handles invalid parameters error response', function () {
    try {
        \request(ErrorResponse::invalidParameters())->post();
    } catch (Exceptions\ApiResponseUnsuccessfulException $e) {
        \expect($e->getMessage())->toBe('Invalid Parameters');
        \expect($e->getErrors())->toHaveKey('beneficiary.type');
        throw $e;
    }
})->throws(Exceptions\ApiResponseUnsuccessfulException::class);
