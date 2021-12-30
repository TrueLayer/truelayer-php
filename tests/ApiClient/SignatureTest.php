<?php

use TrueLayer\Constants\CustomHeaders;
use TrueLayer\Signing\Verifier;

it('signs requests by verb', function () {
    request()->post();
    expect(getSentHttpRequests()[1]->getHeaderLine(CustomHeaders::SIGNATURE))->not()->toBeEmpty();

    request()->get();
    expect(getSentHttpRequests()[1]->getHeaderLine(CustomHeaders::SIGNATURE))->toBeEmpty();
});

it('signs requests with valid signature', function () {
    request()->payload([])->post();
    $sentRequest = getSentHttpRequests()[1];

    $verifier = Verifier::verifyWithPemFile(
        __DIR__.'/../Mocks/ec512-public.pem'
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
