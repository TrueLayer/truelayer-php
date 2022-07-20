<?php

declare(strict_types=1);

use TrueLayer\Interfaces\Webhook\PaymentExecutedEventInterface;
use TrueLayer\Tests\Integration\Mocks\Signing;
use TrueLayer\Tests\Integration\Mocks\WebhookPayload;

\it('verifies', function () {
    $body = WebhookPayload::paymentExecuted();
    $webhook = \rawClient([Signing::getPublicKeysResponse()])->create()->webhook();

    $test = false;
    $webhook
        ->body($body)
        ->path(Signing::getPath())
        ->headers(Signing::getHeaders($body))
        ->handler(function (PaymentExecutedEventInterface $evt) use (&$test) {
            expect($evt)->toBeInstanceOf(PaymentExecutedEventInterface::class);
            $test = true;
            var_dump('here');

        })
        ->execute();


    expect($test)->toBeTrue();

});
