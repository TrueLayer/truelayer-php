<?php

declare(strict_types=1);

use TrueLayer\Constants\Currencies;
use TrueLayer\Contracts\Beneficiary\BeneficiaryInterface;
use TrueLayer\Contracts\Sdk\SdkInterface;
use TrueLayer\Contracts\UserInterface;
use TrueLayer\Tests\Mocks\CreatePayment;
use TrueLayer\Tests\Mocks\PaymentResponse;

it('sends correct payload on creation', function () {
    $factory = CreatePayment::responses([ PaymentResponse::created()]);
    $factory->payment($factory->sortCodeBeneficiary(), $factory->newUser())->create();

    expect(getRequestPayload(1))->toMatchArray([
        'amount_in_minor' => 1,
        'currency' => Currencies::GBP,
        'payment_method' => [
            'type' => 'bank_transfer',
            'statement_reference' => 'Statement ref',
        ],
        'beneficiary' => [
            'scheme_identifier' => [
                'account_number' => '12345678',
                'sort_code' => '010203',
                'type' => 'sort_code_account_number'
            ],
            'reference' => 'The ref',
            'name' => 'John Doe',
            'type' => 'external_account'
        ]
    ]);
});

it('sends correct user payload on creation', function () {
    $factory = CreatePayment::responses([ PaymentResponse::created(), PaymentResponse::created() ]);
    $factory->payment($factory->sortCodeBeneficiary(), $factory->newUser())->create();
    $factory->payment($factory->sortCodeBeneficiary(), $factory->existingUser())->create();

    expect(getRequestPayload(1))->toMatchArray([
        'user' => [
            'name' =>'Alice',
            'phone' => '+447837485713',
            'email' => 'alice@truelayer.com',
            'type' => 'new'
        ],
    ]);

    expect(getRequestPayload(2))->toMatchArray([
        'user' => [
            'id' => '64f800c1-ff48-411f-af78-464725376059',
            'type' => 'existing'
        ],
    ]);
});

it('parses payment creation response correctly', function () {
    $factory = CreatePayment::responses([ PaymentResponse::created() ]);
    $payment = $factory->payment($factory->sortCodeBeneficiary(), $factory->newUser())->create();

    expect($payment->getResourceToken())->toBe(PaymentResponse::CREATED['resource_token']);
    expect($payment->getId())->toBe(PaymentResponse::CREATED['id']);
    expect($payment->getUserId())->toBe(PaymentResponse::CREATED['user']['id']);
});

//it('parses payment retrieval response correctly', function () {
//
//});

