<?php

declare(strict_types=1);

use TrueLayer\Exceptions\ValidationException;
use TrueLayer\Tests\Mocks\CreatePayment;
use TrueLayer\Tests\Mocks\PaymentResponse;

\it('generates HPP url', function () {
    $url = \sdk()->hostedPaymentsPage()
        ->paymentId('1')
        ->paymentToken('1')
        ->returnUri('http://www.return.com')
        ->primaryColour('#111')
        ->secondaryColour('#222222')
        ->tertiaryColour('#333333')
        ->toUrl();

    \expect($url)->toEndWith(
        '#payment_id=1&payment_token=1&return_uri=http%3A%2F%2Fwww.return.com&c_primary=111&c_secondary=222222&c_tertiary=333333'
    );
});

\it('generates HPP url from created payment response', function () {
    $factory = CreatePayment::responses([PaymentResponse::created()]);
    $result = $factory->payment($factory->sortCodeBeneficiary(), $factory->newUser())->create();

    $url = $result->hostedPaymentsPage()
        ->returnUri('http://www.return.com')
        ->primaryColour('#111')
        ->secondaryColour('#222222')
        ->tertiaryColour('#333333')
        ->toUrl();

    \expect($url)->toEndWith(
        '#payment_id=5a2a0a0d-d3ad-4740-860b-45a01bcc17ac&payment_token=the-token&return_uri=http%3A%2F%2Fwww.return.com&c_primary=111&c_secondary=222222&c_tertiary=333333'
    );
});

\it('validates input', function () {
    try {
        \sdk()->hostedPaymentsPage()
            ->primaryColour('1st')
            ->secondaryColour('2nd')
            ->tertiaryColour('3rd')
            ->toUrl();
    } catch (ValidationException $e) {
        \expect($e->getErrors())->toHaveKeys([
            'payment_id',
            'payment_token',
            'return_uri',
            'c_primary',
            'c_secondary',
            'c_tertiary',
        ]);

        throw $e;
    }
})->throws(ValidationException::class);
