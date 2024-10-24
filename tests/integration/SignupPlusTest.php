<?php

declare(strict_types=1);

use TrueLayer\Interfaces\SignupPlus\SignupPlusAuthUriCreatedInterface;
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
