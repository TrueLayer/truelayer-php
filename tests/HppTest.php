<?php

use TrueLayer\Exceptions\ValidationException;
use TrueLayer\Tests\Mocks\CreatePayment;
use TrueLayer\Tests\Mocks\PaymentResponse;

it('generates HPP url', function () {
    $url = sdk()->hostedPaymentsPage()
        ->paymentId('1')
        ->resourceToken('1')
        ->returnUri('http://www.return.com')
        ->primary('#111')
        ->secondary('#222222')
        ->tertiary('#333333')
        ->toString();

    expect($url)->toEndWith(
        '#payment_id=1&resource_token=1&return_uri=http%3A%2F%2Fwww.return.com&c_primary=111&c_secondary=222222&c_tertiary=333333'
    );
});

it('generates HPP url from created payment response', function () {
    $factory = CreatePayment::responses([ PaymentResponse::created()]);
    $result = $factory->payment($factory->sortCodeBeneficiary(), $factory->newUser())->create();

    $url = $result->hostedPaymentsPage()
        ->returnUri('http://www.return.com')
        ->primary('#111')
        ->secondary('#222222')
        ->tertiary('#333333')
        ->toString();

    expect($url)->toEndWith(
        '#payment_id=5a2a0a0d-d3ad-4740-860b-45a01bcc17ac&resource_token=the-token&return_uri=http%3A%2F%2Fwww.return.com&c_primary=111&c_secondary=222222&c_tertiary=333333'
    );
});

it('validates input', function () {
    try {
        sdk()->hostedPaymentsPage()
            ->primary('1st')
            ->secondary('2nd')
            ->tertiary('3rd')
            ->toString();
    } catch (ValidationException $e) {
        expect($e->getErrors())->toHaveKeys([
            'payment_id',
            'resource_token',
            'return_uri',
            'c_primary',
            'c_secondary',
            'c_tertiary'
        ]);

        throw $e;
    }
})->throws(ValidationException::class);
