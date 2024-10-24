<?php

declare(strict_types=1);

use TrueLayer\Constants\AccountIdentifierTypes;
use TrueLayer\Constants\BeneficiaryTypes;
use TrueLayer\Constants\Currencies;
use TrueLayer\Interfaces\Payout\PayoutCreatedInterface;
use TrueLayer\Tests\Integration\Mocks\PayoutResponse;

\it('sends correct payload on open loop payout', function () {
    $client = \client(PayoutResponse::created());

    $accountIdentifier = $client->accountIdentifier()
        ->iban()
        ->iban('GB29NWBK60161331926819');

    $beneficiary = $client->payoutBeneficiary()->externalAccount()
        ->accountHolderName('Test')
        ->reference('Test reference')
        ->accountIdentifier($accountIdentifier)
        ->dateOfBirth('1990-01-31');

    $beneficiary->address(null)
        ->addressLine1('The Gilbert')
        ->addressLine2('City of')
        ->city('London')
        ->state('Greater London')
        ->zip('EC2A 1PX')
        ->countryCode('GB');

    $client->payout()
        ->amountInMinor(1)
        ->currency('GBP')
        ->merchantAccountId('1234')
        ->beneficiary($beneficiary)
        ->create();

    \expect(\getRequestPayload(1))->toMatchArray([
        'amount_in_minor' => 1,
        'currency' => Currencies::GBP,
        'merchant_account_id' => '1234',
        'beneficiary' => [
            'type' => BeneficiaryTypes::EXTERNAL_ACCOUNT,
            'account_holder_name' => 'Test',
            'reference' => 'Test reference',
            'account_identifier' => [
                'type' => AccountIdentifierTypes::IBAN,
                'iban' => 'GB29NWBK60161331926819',
            ],
            'address' => [
                'address_line1' => 'The Gilbert',
                'address_line2' => 'City of',
                'city' => 'London',
                'state' => 'Greater London',
                'zip' => 'EC2A 1PX',
                'country_code' => 'GB',
            ],
            'date_of_birth' => '1990-01-31',
        ],
    ]);
});

\it('sends correct payload on closed loop payout', function () {
    $client = \client(PayoutResponse::created());

    $beneficiary = $client->payoutBeneficiary()->paymentSource()
        ->paymentSourceId('source1')
        ->reference('Test reference')
        ->userId('user1');

    $client->payout()
        ->amountInMinor(1)
        ->currency('GBP')
        ->merchantAccountId('1234')
        ->beneficiary($beneficiary)
        ->create();

    \expect(\getRequestPayload(1))->toMatchArray([
        'amount_in_minor' => 1,
        'currency' => Currencies::GBP,
        'merchant_account_id' => '1234',
        'beneficiary' => [
            'type' => BeneficiaryTypes::PAYMENT_SOURCE,
            'payment_source_id' => 'source1',
            'user_id' => 'user1',
            'reference' => 'Test reference',
        ],
    ]);
});

\it('sends correct payload on a payout to a business account', function () {
    $client = \client(PayoutResponse::created());

    $beneficiary = $client->payoutBeneficiary()->businessAccount()
        ->reference('Test reference');

    $client->payout()
        ->amountInMinor(1)
        ->currency('GBP')
        ->merchantAccountId('1234')
        ->beneficiary($beneficiary)
        ->create();

    \expect(\getRequestPayload(1))->toMatchArray([
        'amount_in_minor' => 1,
        'currency' => Currencies::GBP,
        'merchant_account_id' => '1234',
        'beneficiary' => [
            'type' => BeneficiaryTypes::BUSINESS_ACCOUNT,
            'reference' => 'Test reference',
        ],
    ]);
});

\it('sends correct metadata on creation', function (array $metadata) {
    $client = \client(PayoutResponse::created());

    $beneficiary = $client->payoutBeneficiary()->businessAccount()
        ->reference('Test reference');

    $client->payout()
        ->amountInMinor(1)
        ->currency('GBP')
        ->merchantAccountId('1234')
        ->beneficiary($beneficiary)
        ->metadata($metadata)
        ->create();

    \expect(\getRequestPayload(1))->toMatchArray([
        'amount_in_minor' => 1,
        'currency' => Currencies::GBP,
        'merchant_account_id' => '1234',
        'beneficiary' => [
            'type' => BeneficiaryTypes::BUSINESS_ACCOUNT,
            'reference' => 'Test reference',
        ],
        'metadata' => empty($metadata) ? null : $metadata,
    ]);
})->with([
    'some metadata' => [
        ['foo' => 'bar'],
    ],
    'no metadata' => [[]],
]);

\it('parses payout creation response correctly', function () {
    $client = \client(PayoutResponse::created());

    $accountIdentifier = $client->accountIdentifier()
        ->iban()
        ->iban('GB29NWBK60161331926819');

    $beneficiary = $client->payoutBeneficiary()->externalAccount()
        ->accountHolderName('Test')
        ->reference('Test reference')
        ->accountIdentifier($accountIdentifier);

    $response = $client->payout()
        ->amountInMinor(1)
        ->currency('GBP')
        ->merchantAccountId('1234')
        ->beneficiary($beneficiary)
        ->create();

    \expect($response)->toBeInstanceOf(PayoutCreatedInterface::class);
    \expect($response->getId())->toBe('ca9a3154-9151-44cf-b7cb-073c9e12ef91');
});

\it('uses custom idempotency key', function () {
    $client = \client(PayoutResponse::created());

    $accountIdentifier = $client->accountIdentifier()
        ->iban()
        ->iban('GB29NWBK60161331926819');

    $beneficiary = $client->payoutBeneficiary()->externalAccount()
        ->accountHolderName('Test')
        ->reference('Test reference')
        ->accountIdentifier($accountIdentifier);

    $requestOptions = $client->requestOptions()->idempotencyKey('payout-test-idempotency-key');

    $client->payout()
        ->amountInMinor(1)
        ->currency('GBP')
        ->merchantAccountId('1234')
        ->beneficiary($beneficiary)
        ->requestOptions($requestOptions)
        ->create();

    $sentIdempotencyKey = \getRequestHeader(1, TrueLayer\Constants\CustomHeaders::IDEMPOTENCY_KEY)[0];

    \expect($sentIdempotencyKey)->toBe('payout-test-idempotency-key');
});
