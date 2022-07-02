<?php

declare(strict_types=1);

use TrueLayer\Tests\Integration\Mocks\PaymentResponse;

\it('adds user agent header', function () {
    \client(PaymentResponse::executed())->getPayment('1');

    $authRequestUserAgent = \getSentHttpRequests()[0]->getHeaderLine('User-Agent');
    $apiRequestUserAgent = \getSentHttpRequests()[1]->getHeaderLine('User-Agent');

    expect($authRequestUserAgent)->toBe('truelayer-php/unknown');
    expect($apiRequestUserAgent)->toBe('truelayer-php/unknown');
});
