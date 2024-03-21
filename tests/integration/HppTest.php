<?php

declare(strict_types=1);

use TrueLayer\Exceptions\ValidationException;
use TrueLayer\Tests\Integration\Mocks\CreatePayment;
use TrueLayer\Tests\Integration\Mocks\PaymentResponse;

\it('generates HPP url', function () {
    $url = \client()->hostedPaymentsPage()
        ->paymentId('1')
        ->resourceToken('1')
        ->returnUri('http://www.return.com')
        ->primaryColour('#111')
        ->secondaryColour('#222222')
        ->tertiaryColour('#333333')
        ->toUrl();

    \expect($url)->toEndWith(
        '#payment_id=1&resource_token=1&return_uri=http%3A%2F%2Fwww.return.com&c_primary=111&c_secondary=222222&c_tertiary=333333'
    );
});

\it('generates HPP url from created payment response', function () {
    $factory = CreatePayment::responses([PaymentResponse::created()]);
    $result = $factory->payment($factory->newUser(), $factory->bankTransferMethod($factory->sortCodeBeneficiary()))->create();

    $url = $result->hostedPaymentsPage()
        ->returnUri('http://www.return.com')
        ->primaryColour('#111')
        ->secondaryColour('#222222')
        ->tertiaryColour('#333333')
        ->toUrl();

    \expect($url)->toEndWith(
        '#payment_id=5a2a0a0d-d3ad-4740-860b-45a01bcc17ac&resource_token=the-token&return_uri=http%3A%2F%2Fwww.return.com&c_primary=111&c_secondary=222222&c_tertiary=333333'
    );
});
