<?php

declare(strict_types=1);

use GuzzleHttp\Psr7\Response;
use TrueLayer\Constants\Countries;
use TrueLayer\Constants\Currencies;
use TrueLayer\Constants\CustomerSegments;
use TrueLayer\Constants\ReleaseChannels;
use TrueLayer\Interfaces\Client\ClientInterface;

it('searches payment providers with minimal configuration', function () {
    $mockResponse = new Response(200, [], \json_encode([
        'items' => [
            [
                'id' => 'mock-payments-gb-redirect',
                'display_name' => 'Mock Bank',
                'icon_uri' => 'https://example.com/icon.svg',
                'logo_uri' => 'https://example.com/logo.svg',
                'bg_color' => '#000000',
                'country_code' => Countries::GB,
                'capabilities' => [
                    'payments' => [
                        'bank_transfer' => (object) [],
                    ],
                ],
            ],
        ],
    ]));

    $client = \client([$mockResponse]);

    $config = $client->searchProvidersRequest()->authorizationFlowConfiguration();
    $config->redirect();

    $request = $client->searchProvidersRequest()->create();
    $request->authorizationFlowConfiguration($config);

    $providers = $client->searchPaymentsProviders($request->toArray());

    expect($providers)->toBeArray();
    expect($providers)->toHaveCount(1);
    expect($providers[0]->getId())->toBe('mock-payments-gb-redirect');
    expect($providers[0]->getDisplayName())->toBe('Mock Bank');
    expect($providers[0]->getCountryCode())->toBe(Countries::GB);
});

it('searches payment providers with full configuration', function () {
    $mockResponse = new Response(200, [], \json_encode([
        'items' => [
            [
                'id' => 'mock-payments-gb-redirect',
                'display_name' => 'Mock Bank',
                'icon_uri' => 'https://example.com/icon.svg',
                'logo_uri' => 'https://example.com/logo.svg',
                'bg_color' => '#000000',
                'country_code' => Countries::GB,
                'swift_code' => 'MOCKGB01XXX',
                'capabilities' => [
                    'payments' => [
                        'bank_transfer' => (object) [],
                    ],
                ],
                'bin_ranges' => [
                    ['min' => '123456', 'max' => '123499'],
                ],
            ],
        ],
    ]));

    $client = \client([$mockResponse]);

    $config = $client->searchProvidersRequest()->authorizationFlowConfiguration();
    $config->redirect();
    $config->providerSelection();
    $config->form(['text', 'select']);
    $config->consent('supported');

    $capabilities = $client->searchProvidersRequest()->capabilities();
    $capabilities->bankTransfer();

    $request = $client->searchProvidersRequest()->create();
    $request->authorizationFlowConfiguration($config);
    $request->capabilities($capabilities);
    $request->countries([Countries::GB]);
    $request->currencies([Currencies::GBP]);
    $request->customerSegments([CustomerSegments::RETAIL]);
    $request->releaseChannel(ReleaseChannels::GENERAL_AVAILABILITY);

    $providers = $client->searchPaymentsProviders($request->toArray());

    expect($providers)->toBeArray();
    expect($providers)->toHaveCount(1);
    expect($providers[0]->getId())->toBe('mock-payments-gb-redirect');
    expect($providers[0]->getDisplayName())->toBe('Mock Bank');
    expect($providers[0]->getSwiftCode())->toBe('MOCKGB01XXX');
    expect($providers[0]->getBinRanges())->toBeArray();
});

it('sends correct payload for search providers request', function () {
    $mockResponse = new Response(200, [], \json_encode(['items' => []]));
    $client = \client([$mockResponse]);

    $config = $client->searchProvidersRequest()->authorizationFlowConfiguration();
    $config->redirect();
    $config->providerSelectionWithIcon('large');
    $config->form(['text']);

    $capabilities = $client->searchProvidersRequest()->capabilities();
    $capabilities->bankTransfer();

    $request = $client->searchProvidersRequest()->create();
    $request->authorizationFlowConfiguration($config);
    $request->capabilities($capabilities);
    $request->countries([Countries::GB, Countries::FR]);
    $request->currencies([Currencies::GBP, Currencies::EUR]);
    $request->customerSegments([CustomerSegments::RETAIL, CustomerSegments::BUSINESS]);
    $request->releaseChannel(ReleaseChannels::PUBLIC_BETA);

    $client->searchPaymentsProviders($request->toArray());

    $payload = \getRequestPayload(1);

    expect($payload)->toHaveKey('authorization_flow');
    expect($payload['authorization_flow']['configuration'])->toHaveKey('redirect');
    expect($payload['authorization_flow']['configuration'])->toHaveKey('provider_selection');
    expect($payload['authorization_flow']['configuration']['provider_selection'])->toHaveKey('icon');
    expect($payload['authorization_flow']['configuration']['provider_selection']['icon']['size'])->toBe('large');
    expect($payload['authorization_flow']['configuration'])->toHaveKey('form');
    expect($payload['authorization_flow']['configuration']['form']['input_types'])->toBe(['text']);
    expect($payload['capabilities']['payments'])->toHaveKey('bank_transfer');
    expect($payload['countries'])->toBe([Countries::GB, Countries::FR]);
    expect($payload['currencies'])->toBe([Currencies::GBP, Currencies::EUR]);
    expect($payload['customer_segments'])->toBe([CustomerSegments::RETAIL, CustomerSegments::BUSINESS]);
    expect($payload['release_channel'])->toBe(ReleaseChannels::PUBLIC_BETA);
});

it('searches with mandates capabilities', function () {
    $mockResponse = new Response(200, [], \json_encode(['items' => []]));
    $client = \client([$mockResponse]);

    $config = $client->searchProvidersRequest()->authorizationFlowConfiguration();
    $config->redirect();

    $capabilities = $client->searchProvidersRequest()->capabilities();
    $capabilities->vrpCommercial();
    $capabilities->vrpSweeping();

    $request = $client->searchProvidersRequest()->create();
    $request->authorizationFlowConfiguration($config);
    $request->capabilities($capabilities);

    $client->searchPaymentsProviders($request->toArray());

    $payload = \getRequestPayload(1);

    expect($payload['capabilities']['mandates'])->toBeArray();
    expect($payload['capabilities']['mandates'])->toHaveKey('vrp_commercial');
    expect($payload['capabilities']['mandates'])->toHaveKey('vrp_sweeping');
});
