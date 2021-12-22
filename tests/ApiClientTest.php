<?php

use GuzzleHttp\Psr7\Response;
use TrueLayer\Tests\Mocks;
use TrueLayer\Exceptions;
use TrueLayer\Constants\CustomHeaders;

it('validates payload', function () {
    $request = request()
        ->payload([
            'param' => 123,
            'valid' => 'test',
            'nested' => [ 'param' => 456 ],
        ])
        ->requestRules(fn() => [
            'param' => 'required|email',
            'valid' => 'required|string',
            'nested.param' => 'required|string'
        ]);

    try {
        $request->post();
    } catch (Exceptions\ApiRequestValidationException $e) {
        expect($e->getMessage())->toContain('invalid');
        expect($e->getErrors())->toHaveKeys(['param', 'nested.param']);
        expect($e->getErrors())->not()->toHaveKey('valid');
        throw $e;
    }
})->throws(Exceptions\ApiRequestValidationException::class);

it('appends access token', function () {
    request()->post();
    $auth = getSentHttpRequests()[1]->getHeaderLine('Authorization');
    expect($auth)->toBe('Bearer ' . Mocks\AuthResponse::ACCESS_TOKEN);
});

it('signs requests by verb', function () {
    request()->post();
    expect(getSentHttpRequests()[1]->getHeaderLine(CustomHeaders::SIGNATURE))->not()->toBeEmpty();

    request()->get();
    expect(getSentHttpRequests()[1]->getHeaderLine(CustomHeaders::SIGNATURE))->toBeEmpty();
});

it('signs requests with valid signature', function () {
    request()->payload([])->post();
    $sentRequest = getSentHttpRequests()[1];

    $verifier = \TrueLayer\Signing\Verifier::verifyWithPemFile(
        __DIR__.'/Mocks/ec512-public.pem'
    );

    $signature = $sentRequest->getHeaderLine(CustomHeaders::SIGNATURE);
    $idempotencyKey = $sentRequest->getHeaderLine(CustomHeaders::IDEMPOTENCY_KEY);

    $verifier
        ->path($sentRequest->getUri()->getPath())
        ->headers([ CustomHeaders::IDEMPOTENCY_KEY => $idempotencyKey ])
        ->body('[]')
        ->method('POST')
        ->verify($signature);
})->expectNotToPerformAssertions();

it('handles invalid parameters response', function () {
    $body = '{"type":"https://docs.truelayer.com/docs/error-types#invalid-parameters","title":"Invalid Parameters","status":400,"detail":"The request body was invalid.","trace_id":"8246b93e2f53ad4a9a4b9af884dcf457","errors":{"beneficiary":["Value is required."],"user":["Value is required."]}}';
    try {
        request(new Response(400, [], $body))->post();
    } catch (Exceptions\ApiResponseUnsuccessfulException $e) {
        expect($e->getMessage())->toBe('Invalid Parameters');
        expect($e->getErrors())->toHaveKeys(['beneficiary', 'user']);
        throw $e;
    }
})->throws(Exceptions\ApiResponseUnsuccessfulException::class);

it('validates response data', function () {
    $body = '{"test": "value", "username":"alex"}';

    try {
        request(new Response(200, [], $body))
            ->responseRules(fn() => [
                'test' => 'string',
                'username' => 'email'
            ])
            ->post();
    } catch (Exceptions\ApiResponseValidationException $e) {
        expect($e->getErrors())->toHaveKey('username');
        expect($e->getErrors())->not()->toHaveKey('test');
        throw($e);
    }
})->throws(Exceptions\ApiResponseValidationException::class);

\it('retries requests', function () {
    // todo
    // also assert idempotency key too
});
