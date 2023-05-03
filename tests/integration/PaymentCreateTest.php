<?php

declare(strict_types=1);

use TrueLayer\Constants\Countries;
use TrueLayer\Constants\Currencies;
use TrueLayer\Constants\CustomerSegments;
use TrueLayer\Constants\PaymentMethods;
use TrueLayer\Constants\ReleaseChannels;
use TrueLayer\Tests\Integration\Mocks\CreatePayment;
use TrueLayer\Tests\Integration\Mocks\PaymentResponse;

\it('sends correct payload on creation', function () {
    $factory = CreatePayment::responses([PaymentResponse::created()]);
    $factory->payment($factory->newUser(), $factory->bankTransferMethod($factory->sortCodeBeneficiary()))->create();

    \expect(\getRequestPayload(1))->toMatchArray([
        'amount_in_minor' => 1,
        'currency' => Currencies::GBP,
        'metadata' => [
            "metadata_key_1" => "metadata_value_1",
            "metadata_key_2" => "metadata_value_2",
            "metadata_key_3" => "metadata_value_3",
        ],
        'payment_method' => [
            'type' => PaymentMethods::BANK_TRANSFER,
            'beneficiary' => [
                'account_identifier' => [
                    'account_number' => '12345678',
                    'sort_code' => '010203',
                    'type' => 'sort_code_account_number',
                ],
                'reference' => 'The ref',
                'account_holder_name' => 'John Doe',
                'type' => 'external_account',
            ],
            'provider_selection' => [
                'type' => PaymentMethods::PROVIDER_TYPE_USER_SELECTION,
                'filter' => [
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
                    'excludes' => [
                        'provider_ids' => [],
                    ],
                ],
            ],
        ],
    ]);
});

\it('sends correct user payload on creation', function () {
    $factory = CreatePayment::responses([PaymentResponse::created(), PaymentResponse::created()]);
    $factory->payment($factory->newUser(), $factory->bankTransferMethod($factory->sortCodeBeneficiary()))->create();
    $factory->payment($factory->existingUser(), $factory->bankTransferMethod($factory->sortCodeBeneficiary()))->create();

    \expect(\getRequestPayload(1))->toMatchArray([
        'user' => [
            'id' => null,
            'name' => 'Alice',
            'phone' => '+447837485713',
            'email' => 'alice@truelayer.com',
        ],
    ]);

    \expect(\getRequestPayload(2))->toMatchArray([
        'user' => [
            'id' => '64f800c1-ff48-411f-af78-464725376059',
            'name' => null,
            'phone' => null,
            'email' => null,
        ],
    ]);
});

\it('parses payment creation response correctly', function () {
    $factory = CreatePayment::responses([PaymentResponse::created()]);
    $payment = $factory->payment($factory->newUser(), $factory->bankTransferMethod($factory->sortCodeBeneficiary()))->create();

    \expect($payment->getResourceToken())->toBe(PaymentResponse::CREATED['resource_token']);
    \expect($payment->getId())->toBe(PaymentResponse::CREATED['id']);
    \expect($payment->getUserId())->toBe(PaymentResponse::CREATED['user']['id']);
});
