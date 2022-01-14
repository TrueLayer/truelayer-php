<?php

declare(strict_types=1);

use TrueLayer\Constants\Countries;
use TrueLayer\Constants\Currencies;
use TrueLayer\Constants\CustomerSegments;
use TrueLayer\Constants\PaymentMethods;
use TrueLayer\Constants\ReleaseChannels;
use TrueLayer\Tests\Mocks\CreatePayment;
use TrueLayer\Tests\Mocks\PaymentResponse;

\it('sends correct payload on creation', function () {
    $factory = CreatePayment::responses([PaymentResponse::created()]);
    $factory->payment($factory->sortCodeBeneficiary(), $factory->newUser(), $factory->paymentMethod())->create();

    \expect(\getRequestPayload(1))->toMatchArray([
        'amount_in_minor' => 1,
        'currency' => Currencies::GBP,
        'payment_method' => [
            'type' => PaymentMethods::BANK_TRANSFER,
            'statement_reference' => 'Statement ref',
            'provider_filter' => [
                'countries' => [
                    Countries::GB,
                ],
                'release_channel' => ReleaseChannels::PRIVATE_BETA,
                'customer_segments' => [
                    CustomerSegments::RETAIL,
                ],
                'provider_ids' => [
                    'mock-payments-gb-redirect',
                ],
            ],
        ],
        'beneficiary' => [
            'scheme_identifier' => [
                'account_number' => '12345678',
                'sort_code' => '010203',
                'type' => 'sort_code_account_number',
            ],
            'reference' => 'The ref',
            'name' => 'John Doe',
            'type' => 'external_account',
        ],
    ]);
});

\it('sends correct user payload on creation', function () {
    $factory = CreatePayment::responses([PaymentResponse::created(), PaymentResponse::created()]);
    $factory->payment($factory->sortCodeBeneficiary(), $factory->newUser(), $factory->paymentMethod())->create();
    $factory->payment($factory->sortCodeBeneficiary(), $factory->existingUser(), $factory->paymentMethod())->create();

    \expect(\getRequestPayload(1))->toMatchArray([
        'user' => [
            'name' => 'Alice',
            'phone' => '+447837485713',
            'email' => 'alice@truelayer.com',
            'type' => 'new',
        ],
    ]);

    \expect(\getRequestPayload(2))->toMatchArray([
        'user' => [
            'id' => '64f800c1-ff48-411f-af78-464725376059',
            'type' => 'existing',
        ],
    ]);
});

\it('parses payment creation response correctly', function () {
    $factory = CreatePayment::responses([PaymentResponse::created()]);
    $payment = $factory->payment($factory->sortCodeBeneficiary(), $factory->newUser(), $factory->paymentMethod())->create();

    \expect($payment->getPaymentToken())->toBe(PaymentResponse::CREATED['payment_token']);
    \expect($payment->getId())->toBe(PaymentResponse::CREATED['id']);
    \expect($payment->getUserId())->toBe(PaymentResponse::CREATED['user']['id']);
});
