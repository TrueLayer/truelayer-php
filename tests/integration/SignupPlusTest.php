<?php

declare(strict_types=1);

use TrueLayer\Interfaces\AddressRetrievedInterface;
use TrueLayer\Interfaces\SignupPlus\SignupPlusAccountDetailsInterface;
use TrueLayer\Interfaces\SignupPlus\SignupPlusAuthUriCreatedInterface;
use TrueLayer\Interfaces\SignupPlus\SignupPlusUserDataRetrievedInterface;
use TrueLayer\Tests\Integration\Mocks\SignupPlusResponse;

\it('sends correct payload on signup plus auth link creation', function (string $paymentId, ?string $state) {
    $client = \client(SignupPlusResponse::authUriCreated());

    $request = $client->signupPlus()
        ->authUri()
        ->paymentId($paymentId);

    if (!empty($state)) {
        $request->state($state);
    }

    $request->create();

    \expect(\getRequestPayload(1))->toMatchArray([
        'payment_id' => $paymentId,
        'state' => $state,
    ]);
})->with([
    'no state' => ['fake_payment_id', null],
    'with state' => ['fake_payment_id', 'fake_state'],
]);

\it('parses the signup plus auth uri creation response correctly', function () {
    $client = \client(SignupPlusResponse::authUriCreated());

    $response = $client->signupPlus()
        ->authUri()
        ->paymentId('fake_payment_id')
        ->state('fake_state')
        ->create();

    \expect($response)->toBeInstanceOf(SignupPlusAuthUriCreatedInterface::class);
    \expect($response->getAuthUri())->toBeString();
});

\it('retrieves the user data by payment id', function () {
    $client = \client(SignupPlusResponse::userDataRetrievedFinland());

    $response = $client->signupPlus()
        ->userData()
        ->paymentId('fake_payment_id')
        ->retrieve();

    \expect($response)->toBeInstanceOf(SignupPlusUserDataRetrievedInterface::class);

    \expect($response->getTitle())->toBeNull();
    \expect($response->getFirstName())->toBe('Tero Testi');
    \expect($response->getLastName())->toBe('Äyrämö');
    \expect($response->getNationalIdentificationNumber())->toBe('010170-1234');
    \expect($response->getSex())->toBe('M');

    $address = $response->getAddress();
    \expect($address)->toBeInstanceOf(AddressRetrievedInterface::class);
    \expect($address->getAddressLine1())->toBe('Kauppa Puistikko 6 B 15');
    \expect($address->getCity())->toBe('VAASA');
    \expect($address->getZip())->toBe('65100');

    $accountDetails = $response->getAccountDetails();
    \expect($accountDetails)->toBeInstanceOf(SignupPlusAccountDetailsInterface::class);
    \expect($accountDetails->getIban())->toBe('FI53CLRB04066200002723');
    \expect($accountDetails->getProviderId())->toBe('fi-op');
});

\it('retrieves the user data by mandate id', function () {
    $client = \client(SignupPlusResponse::userDataRetrievedUk());

    $response = $client->signupPlus()
        ->userData()
        ->mandateId('fake_mandate_id')
        ->retrieve();

    \expect($response)->toBeInstanceOf(SignupPlusUserDataRetrievedInterface::class);

    \expect($response->getTitle())->toBe('Mr');
    \expect($response->getFirstName())->toBe('Sherlock');
    \expect($response->getLastName())->toBe('Holmes');
    \expect($response->getDateOfBirth())->toBe('1854-01-06');

    $address = $response->getAddress();
    \expect($address)->toBeInstanceOf(AddressRetrievedInterface::class);
    \expect($address->getAddressLine1())->toBe('221B Baker St');
    \expect($address->getAddressLine2())->toBe('Flat 2');
    \expect($address->getCity())->toBe('London');
    \expect($address->getState())->toBe('Greater London');
    \expect($address->getZip())->toBe('NW1 6XE');

    $accountDetails = $response->getAccountDetails();
    \expect($accountDetails)->toBeInstanceOf(SignupPlusAccountDetailsInterface::class);
    \expect($accountDetails->getAccountNumber())->toBe('41921234');
    \expect($accountDetails->getSortCode())->toBe('04-01-02');
    \expect($accountDetails->getIban())->toBe('GB71MONZ04435141923452');
    \expect($accountDetails->getProviderId())->toBe('ob-monzo');
});
