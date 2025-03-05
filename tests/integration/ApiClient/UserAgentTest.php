<?php

declare(strict_types=1);

use Composer\InstalledVersions;
use TrueLayer\Constants\CustomHeaders;
use TrueLayer\Tests\Integration\Mocks\PaymentResponse;

\it('adds user agent header', function () {
    \client(PaymentResponse::executed())->getPayment('1');

    $authRequestUserAgent = \getSentHttpRequests()[0]->getHeaderLine(CustomHeaders::TL_AGENT);
    $apiRequestUserAgent = \getSentHttpRequests()[1]->getHeaderLine(CustomHeaders::TL_AGENT);

    $version = InstalledVersions::getPrettyVersion('truelayer/client');
    $userAgent = "truelayer-php / {$version}";

    \expect($authRequestUserAgent)->toStartWith($userAgent);
    \expect($apiRequestUserAgent)->toStartWith($userAgent);
});

\it('uses custom agent header', function () {
    TrueLayer\Settings::tlAgent('test');
    \client(PaymentResponse::executed())->getPayment('1');

    $authRequestUserAgent = \getSentHttpRequests()[0]->getHeaderLine(CustomHeaders::TL_AGENT);
    $apiRequestUserAgent = \getSentHttpRequests()[1]->getHeaderLine(CustomHeaders::TL_AGENT);

    \expect($authRequestUserAgent)->toBe('test');
    \expect($apiRequestUserAgent)->toBe('test');
});
