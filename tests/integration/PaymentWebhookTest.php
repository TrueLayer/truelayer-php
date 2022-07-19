<?php

declare(strict_types=1);

use TrueLayer\Tests\Integration\Mocks\Signing;
use TrueLayer\Tests\Integration\Mocks\WebhookPayload;

\it('verifies', function () {
    $body = WebhookPayload::paymentExecuted();
    $webhook = \rawClient([Signing::getPublicKeysResponse()])->create()->webhook();

    $webhook
        ->body($body)
        ->path(Signing::getPath())
        ->headers(Signing::getHeaders())
        ->execute();
});
