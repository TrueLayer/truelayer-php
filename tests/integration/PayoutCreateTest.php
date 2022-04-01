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
        ->accountIdentifier($accountIdentifier);

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
