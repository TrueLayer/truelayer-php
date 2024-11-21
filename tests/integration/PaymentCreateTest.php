<?php

declare(strict_types=1);

use TrueLayer\Constants\AccountIdentifierTypes;
use TrueLayer\Constants\Countries;
use TrueLayer\Constants\Currencies;
use TrueLayer\Constants\CustomerSegments;
use TrueLayer\Constants\PaymentMethods;
use TrueLayer\Constants\ReleaseChannels;
use TrueLayer\Constants\UserPoliticalExposures;
use TrueLayer\Interfaces\Remitter\RemitterVerification\RemitterVerificationInterface;
use TrueLayer\Interfaces\Scheme\SchemeSelectionInterface;
use TrueLayer\Tests\Integration\Mocks\CreatePayment;
use TrueLayer\Tests\Integration\Mocks\PaymentResponse;

\it('sends correct payload on creation', function () {
    $factory = CreatePayment::responses([PaymentResponse::created()]);
    $factory->payment($factory->newUser(), $factory->bankTransferMethod($factory->sortCodeBeneficiary()))->create();

    \expect(\getRequestPayload(1))->toMatchArray([
        'amount_in_minor' => 1,
        'currency' => Currencies::GBP,
        'metadata' => [
            'metadata_key_1' => 'metadata_value_1',
            'metadata_key_2' => 'metadata_value_2',
            'metadata_key_3' => 'metadata_value_3',
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
                        'provider_ids' => null,
                    ],
                ],
                'scheme_selection' => null,
            ],
            'retry' => null,
        ],
    ]);
});

\it('sends correct payload on creation with preselected provider', function () {
    $factory = CreatePayment::responses([PaymentResponse::created()]);

    $ibanAccountIdentifier = $factory->getClient()
        ->accountIdentifier()
        ->iban()
        ->iban('mock_iban');

    $remitter = $factory->getClient()
        ->remitter()
        ->accountHolderName('John Doe')
        ->accountIdentifier($ibanAccountIdentifier);

    $selection = $factory->getClient()
        ->providerSelection()
        ->preselected()
        ->providerId('mock-payments-gb-redirect')
        ->remitter($remitter)
        ->dataAccessToken('mock_access_token');

    $paymentMethod = $factory->getClient()
        ->paymentMethod()
        ->bankTransfer()
        ->beneficiary($factory->sortCodeBeneficiary())
        ->providerSelection($selection);

    $factory->payment($factory->newUser(), $paymentMethod)->create();

    \expect(\getRequestPayload(1))->toMatchArray([
        'amount_in_minor' => 1,
        'currency' => Currencies::GBP,
        'metadata' => [
            'metadata_key_1' => 'metadata_value_1',
            'metadata_key_2' => 'metadata_value_2',
            'metadata_key_3' => 'metadata_value_3',
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
                'type' => PaymentMethods::PROVIDER_TYPE_PRESELECTED,
                'provider_id' => 'mock-payments-gb-redirect',
                'scheme_selection' => null,
                'remitter' => [
                    'account_holder_name' => 'John Doe',
                    'account_identifier' => [
                        'type' => AccountIdentifierTypes::IBAN,
                        'iban' => 'mock_iban',
                    ],
                ],
                'data_access_token' => 'mock_access_token',
            ],
            'retry' => null,
        ],
    ]);
});

\it('sends the risk assessment on payment creation', function () {
    $factory = CreatePayment::responses([PaymentResponse::created()]);

    $payment = $factory->payment($factory->newUser(), $factory->bankTransferMethod($factory->sortCodeBeneficiary()));
    $payment->riskAssessment()->segment('mock-risk-assessment');

    $payment->create();

    \expect(\getRequestPayload(1))->toMatchArray([
        'amount_in_minor' => 1,
        'currency' => Currencies::GBP,
        'metadata' => [
            'metadata_key_1' => 'metadata_value_1',
            'metadata_key_2' => 'metadata_value_2',
            'metadata_key_3' => 'metadata_value_3',
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
                        'provider_ids' => null,
                    ],
                ],
                'scheme_selection' => null,
            ],
            'retry' => null,
        ],
        'risk_assessment' => [
            'segment' => 'mock-risk-assessment',
        ],
    ]);
});

\it('ensures preselected provider data access token is optional', function () {
    $factory = CreatePayment::responses([PaymentResponse::created()]);

    $ibanAccountIdentifier = $factory->getClient()
        ->accountIdentifier()
        ->iban()
        ->iban('mock_iban');

    $remitter = $factory->getClient()
        ->remitter()
        ->accountHolderName('John Doe')
        ->accountIdentifier($ibanAccountIdentifier);

    $selection = $factory->getClient()
        ->providerSelection()
        ->preselected()
        ->providerId('mock-payments-gb-redirect')
        ->remitter($remitter);

    $paymentMethod = $factory->getClient()
        ->paymentMethod()
        ->bankTransfer()
        ->beneficiary($factory->sortCodeBeneficiary())
        ->providerSelection($selection);

    $factory->payment($factory->newUser(), $paymentMethod)->create();

    \expect(\getRequestPayload(1))->toMatchArray([
        'amount_in_minor' => 1,
        'currency' => Currencies::GBP,
        'metadata' => [
            'metadata_key_1' => 'metadata_value_1',
            'metadata_key_2' => 'metadata_value_2',
            'metadata_key_3' => 'metadata_value_3',
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
                'type' => PaymentMethods::PROVIDER_TYPE_PRESELECTED,
                'provider_id' => 'mock-payments-gb-redirect',
                'scheme_selection' => null,
                'remitter' => [
                    'account_holder_name' => 'John Doe',
                    'account_identifier' => [
                        'type' => AccountIdentifierTypes::IBAN,
                        'iban' => 'mock_iban',
                    ],
                ],
                'data_access_token' => null,
            ],
            'retry' => null,
        ],
    ]);
});

\it('ensures preselected provider remitter is optional', function () {
    $factory = CreatePayment::responses([PaymentResponse::created()]);

    $ibanAccountIdentifier = $factory->getClient()
        ->accountIdentifier()
        ->iban()
        ->iban('mock_iban');

    $remitter = $factory->getClient()
        ->remitter()
        ->accountHolderName('John Doe')
        ->accountIdentifier($ibanAccountIdentifier);

    $selection = $factory->getClient()
        ->providerSelection()
        ->preselected()
        ->providerId('mock-payments-gb-redirect')
        ->dataAccessToken('mock-token');

    $paymentMethod = $factory->getClient()
        ->paymentMethod()
        ->bankTransfer()
        ->beneficiary($factory->sortCodeBeneficiary())
        ->providerSelection($selection);

    $factory->payment($factory->newUser(), $paymentMethod)->create();

    \expect(\getRequestPayload(1))->toMatchArray([
        'amount_in_minor' => 1,
        'currency' => Currencies::GBP,
        'metadata' => [
            'metadata_key_1' => 'metadata_value_1',
            'metadata_key_2' => 'metadata_value_2',
            'metadata_key_3' => 'metadata_value_3',
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
                'type' => PaymentMethods::PROVIDER_TYPE_PRESELECTED,
                'provider_id' => 'mock-payments-gb-redirect',
                'scheme_selection' => null,
                'remitter' => null,
                'data_access_token' => 'mock-token',
            ],
            'retry' => null,
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
            'address' => null,
            'date_of_birth' => null,
            'political_exposure' => null,
        ],
    ]);

    \expect(\getRequestPayload(2))->toMatchArray([
        'user' => [
            'id' => '64f800c1-ff48-411f-af78-464725376059',
            'name' => null,
            'phone' => null,
            'email' => null,
            'address' => null,
            'date_of_birth' => null,
            'political_exposure' => null,
        ],
    ]);
});

\it('sends the right user address', function () {
    $factory = CreatePayment::responses([
        PaymentResponse::created(),
    ]);

    $address = [
        'addressLine1' => 'The Gilbert',
        'addressLine2' => 'City of',
        'city' => 'London',
        'state' => 'Greater London',
        'zip' => 'EC2A 1PX',
        'countryCode' => 'GB',
    ];
    $factory->payment($factory->newUserWithAddress($address), $factory->bankTransferMethod($factory->sortCodeBeneficiary()))->create();

    \expect(\getRequestPayload(1))->toMatchArray([
        'user' => [
            'id' => null,
            'name' => 'Alice',
            'phone' => '+447837485713',
            'email' => 'alice@truelayer.com',
            'address' => [
                'address_line1' => 'The Gilbert',
                'address_line2' => 'City of',
                'city' => 'London',
                'state' => 'Greater London',
                'zip' => 'EC2A 1PX',
                'country_code' => 'GB',
            ],
            'date_of_birth' => null,
            'political_exposure' => null,
        ],
    ]);
});

\it('sets the right user political exposure', function (?string $politicalExposure, ?string $expected) {
    $factory = CreatePayment::responses([
        PaymentResponse::created(),
    ]);

    $user = $politicalExposure
        ? $factory->newUser()->politicalExposure($politicalExposure)
        : $factory->newUser();

    $factory->payment($user, $factory->bankTransferMethod($factory->sortCodeBeneficiary()))->create();

    \expect(\getRequestPayload(1)['user']['political_exposure'])->toBe($expected);
})->with([
    'no political exposure' => [
        'politicalExposure' => null,
        'expected' => null,
    ],
    'current political exposure' => [
        'politicalExposure' => UserPoliticalExposures::CURRENT,
        'expected' => UserPoliticalExposures::CURRENT,
    ],
    'none political exposure' => [
        'politicalExposure' => UserPoliticalExposures::NONE,
        'expected' => UserPoliticalExposures::NONE,
    ],
]);

\it('ensures user address state is optional', function () {
    $factory = CreatePayment::responses([
        PaymentResponse::created(),
    ]);
    $address = [
        'addressLine1' => 'The Gilbert',
        'addressLine2' => 'City of',
        'city' => 'London',
        'zip' => 'EC2A 1PX',
        'countryCode' => 'GB',
    ];
    $factory->payment($factory->newUserWithAddress($address), $factory->bankTransferMethod($factory->sortCodeBeneficiary()))->create();

    \expect(\getRequestPayload(1))->toMatchArray([
        'user' => [
            'id' => null,
            'name' => 'Alice',
            'phone' => '+447837485713',
            'email' => 'alice@truelayer.com',
            'address' => [
                'address_line1' => 'The Gilbert',
                'address_line2' => 'City of',
                'city' => 'London',
                'state' => null,
                'zip' => 'EC2A 1PX',
                'country_code' => 'GB',
            ],
            'date_of_birth' => null,
            'political_exposure' => null,
        ],
    ]);
});

\it('ensures user address addressLine2 is optional', function () {
    $factory = CreatePayment::responses([
        PaymentResponse::created(),
    ]);
    $address = [
        'addressLine1' => 'The Gilbert',
        'city' => 'London',
        'state' => 'Greater London',
        'zip' => 'EC2A 1PX',
        'countryCode' => 'GB',
    ];
    $factory->payment($factory->newUserWithAddress($address), $factory->bankTransferMethod($factory->sortCodeBeneficiary()))->create();

    \expect(\getRequestPayload(1))->toMatchArray([
        'user' => [
            'id' => null,
            'name' => 'Alice',
            'phone' => '+447837485713',
            'email' => 'alice@truelayer.com',
            'address' => [
                'address_line1' => 'The Gilbert',
                'address_line2' => null,
                'city' => 'London',
                'state' => 'Greater London',
                'zip' => 'EC2A 1PX',
                'country_code' => 'GB',
            ],
            'date_of_birth' => null,
            'political_exposure' => null,
        ],
    ]);
});

\it('sends the right date of birth', function () {
    $factory = CreatePayment::responses([
        PaymentResponse::created(),
    ]);

    $factory->payment($factory->newUserWithDateOfBirth('2024-01-01'), $factory->bankTransferMethod($factory->sortCodeBeneficiary()))->create();

    \expect(\getRequestPayload(1))->toMatchArray([
        'user' => [
            'id' => null,
            'name' => 'Alice',
            'phone' => '+447837485713',
            'email' => 'alice@truelayer.com',
            'address' => null,
            'date_of_birth' => '2024-01-01',
            'political_exposure' => null,
        ],
    ]);
});

\it('does not send retry field', function () {
    $factory = CreatePayment::responses([PaymentResponse::created()]);
    $factory->payment($factory->newUser(), $factory->bankTransferMethod($factory->sortCodeBeneficiary()))->create();

    $payload = \json_decode(\getRequestPayload(1, false), false);
    \expect($payload->payment_method->retry)->toBeNull();
});

\it('sends retry field', function () {
    $factory = CreatePayment::responses([PaymentResponse::created()]);
    $method = $factory->bankTransferMethod($factory->sortCodeBeneficiary())->enablePaymentRetry();
    $factory->payment($factory->newUser(), $method)->create();

    $payload = \json_decode(\getRequestPayload(1, false), false);

    \expect($payload->payment_method->retry)->toBeInstanceOf(stdClass::class);
});

\it('parses payment creation response correctly', function () {
    $factory = CreatePayment::responses([PaymentResponse::created()]);
    $payment = $factory->payment($factory->newUser(), $factory->bankTransferMethod($factory->sortCodeBeneficiary()))->create();

    \expect($payment->getResourceToken())->toBe(PaymentResponse::CREATED['resource_token']);
    \expect($payment->getId())->toBe(PaymentResponse::CREATED['id']);
    \expect($payment->getUserId())->toBe(PaymentResponse::CREATED['user']['id']);
});

\it('sends custom idempotency key on payment creation', function () {
    $client = \client([PaymentResponse::created(), PaymentResponse::created()]);
    $factory = new CreatePayment($client);

    $requestOptions = $client->requestOptions()->idempotencyKey('payment-test-idempotency-key');

    $factory
        ->payment($factory->newUser(), $factory->bankTransferMethod($factory->sortCodeBeneficiary()))
        ->requestOptions($requestOptions)
        ->create();

    \expect(\getRequestIdempotencyKey(1))->toBe('payment-test-idempotency-key');
});

\it('sends user selected scheme selection', function (SchemeSelectionInterface $schemeSelection, array $expected) {
    $factory = CreatePayment::responses([PaymentResponse::created()]);

    $providerSelection = $factory->getClient()->providerSelection()
        ->userSelected()
        ->schemeSelection($schemeSelection);

    $paymentMethod = $factory->getClient()->paymentMethod()
        ->bankTransfer()
        ->beneficiary($factory->sortCodeBeneficiary())
        ->providerSelection($providerSelection);

    $factory->payment($factory->newUser(), $paymentMethod)->create();

    \expect(\getRequestPayload(1)['payment_method']['provider_selection'])->toMatchArray([
        'scheme_selection' => $expected,
    ]);
})->with([
    'user selected' => [
        'scheme' => fn () => \client()->schemeSelection()->userSelected(),
        'expected' => ['type' => 'user_selected'],
    ],
    'instant only: default remitter fee' => [
        'scheme' => fn () => \client()->schemeSelection()->instantOnly(),
        'expected' => ['type' => 'instant_only', 'allow_remitter_fee' => false],
    ],
    'instant only: no remitter fee' => [
        'scheme' => fn () => \client()->schemeSelection()->instantOnly()->allowRemitterFee(false),
        'expected' => ['type' => 'instant_only', 'allow_remitter_fee' => false],
    ],
    'instant only: allow remitter fee' => [
        'scheme' => fn () => \client()->schemeSelection()->instantOnly()->allowRemitterFee(true),
        'expected' => ['type' => 'instant_only', 'allow_remitter_fee' => true],
    ],
    'instant preferred: default remitter fee' => [
        'scheme' => fn () => \client()->schemeSelection()->instantPreferred(),
        'expected' => ['type' => 'instant_preferred', 'allow_remitter_fee' => false],
    ],
    'instant preferred: no remitter fee' => [
        'scheme' => fn () => \client()->schemeSelection()->instantPreferred(),
        'expected' => ['type' => 'instant_preferred', 'allow_remitter_fee' => false],
    ],
    'instant preferred: allow remitter fee' => [
        'scheme' => fn () => \client()->schemeSelection()->instantPreferred()->allowRemitterFee(true),
        'expected' => ['type' => 'instant_preferred', 'allow_remitter_fee' => true],
    ],
]);

\it('sends provider preselected scheme selection', function (SchemeSelectionInterface $schemeSelection, array $expected) {
    $factory = CreatePayment::responses([PaymentResponse::created()]);

    $providerSelection = $factory->getClient()
        ->providerSelection()
        ->preselected()
        ->schemeSelection($schemeSelection);

    $paymentMethod = $factory->getClient()->paymentMethod()
        ->bankTransfer()
        ->beneficiary($factory->sortCodeBeneficiary())
        ->providerSelection($providerSelection);

    $factory->payment($factory->newUser(), $paymentMethod)->create();

    \expect(\getRequestPayload(1)['payment_method']['provider_selection'])->toMatchArray([
        'scheme_selection' => $expected,
    ]);
})->with([
    'user selected' => [
        'scheme' => fn () => \client()->schemeSelection()->userSelected(),
        'expected' => ['type' => 'user_selected'],
    ],
    'instant only: default remitter fee' => [
        'scheme' => fn () => \client()->schemeSelection()->instantOnly(),
        'expected' => ['type' => 'instant_only', 'allow_remitter_fee' => false],
    ],
    'instant only: no remitter fee' => [
        'scheme' => fn () => \client()->schemeSelection()->instantOnly()->allowRemitterFee(false),
        'expected' => ['type' => 'instant_only', 'allow_remitter_fee' => false],
    ],
    'instant only: allow remitter fee' => [
        'scheme' => fn () => \client()->schemeSelection()->instantOnly()->allowRemitterFee(true),
        'expected' => ['type' => 'instant_only', 'allow_remitter_fee' => true],
    ],
    'instant preferred: default remitter fee' => [
        'scheme' => fn () => \client()->schemeSelection()->instantPreferred(),
        'expected' => ['type' => 'instant_preferred', 'allow_remitter_fee' => false],
    ],
    'instant preferred: no remitter fee' => [
        'scheme' => fn () => \client()->schemeSelection()->instantPreferred(),
        'expected' => ['type' => 'instant_preferred', 'allow_remitter_fee' => false],
    ],
    'instant preferred: allow remitter fee' => [
        'scheme' => fn () => \client()->schemeSelection()->instantPreferred()->allowRemitterFee(true),
        'expected' => ['type' => 'instant_preferred', 'allow_remitter_fee' => true],
    ],
    'preselected' => [
        'scheme' => fn () => \client()->schemeSelection()->preselected()->schemeId('faster_payments_service'),
        'expected' => ['type' => 'preselected', 'scheme_id' => 'faster_payments_service'],
    ],
]);

\it('sends remitter verification for merchant account payments', function (RemitterVerificationInterface $remitterVerification, array $expected) {
    $factory = CreatePayment::responses([PaymentResponse::created()]);

    $beneficiary = $factory->merchantBeneficiary()->verification($remitterVerification);

    $paymentMethod = $factory->getClient()
        ->paymentMethod()
        ->bankTransfer()
        ->beneficiary($beneficiary);

    $factory->payment($factory->newUser(), $paymentMethod)->create();

    \expect(\getRequestPayload(1)['payment_method']['beneficiary'])->toMatchArray([
        'verification' => $expected,
    ]);
})->with([
    'without name verification, without dob' => [
        'remitterVerification' => fn () => \client()->remitterVerification()->automated(),
        'expected' => ['type' => 'automated', 'remitter_name' => false, 'remitter_date_of_birth' => false],
    ],
    'with name verification, without dob' => [
        'remitterVerification' => fn () => \client()->remitterVerification()->automated()->remitterName(true)->remitterDateOfBirth(false),
        'expected' => ['type' => 'automated', 'remitter_name' => true, 'remitter_date_of_birth' => false],
    ],
    'without name verification, with dob' => [
        'remitterVerification' => fn () => \client()->remitterVerification()->automated()->remitterDateOfBirth(true),
        'expected' => ['type' => 'automated', 'remitter_name' => false, 'remitter_date_of_birth' => true],
    ],
    'with name verification, with dob' => [
        'remitterVerification' => fn () => \client()->remitterVerification()->automated()->remitterName(true)->remitterDateOfBirth(true),
        'expected' => ['type' => 'automated', 'remitter_name' => true, 'remitter_date_of_birth' => true],
    ],
]);

\it('sends statement_reference field', function () {
    $factory = CreatePayment::responses([PaymentResponse::created()]);
    $factory->payment(
        $factory->newUser(),
        $factory->bankTransferMethod(
            $factory->merchantBeneficiary()->statementReference('TEST')
        )
    )->create();

    $payload = \json_decode(\getRequestPayload(1, false), false);

    \expect($payload->payment_method->beneficiary->statement_reference)->toBeString();
    \expect($payload->payment_method->beneficiary->statement_reference)->toBe('TEST');
});
