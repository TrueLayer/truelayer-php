<?php

declare(strict_types=1);

use TrueLayer\Interfaces\Provider\PaymentsProviderInterface;
use TrueLayer\Interfaces\Provider\SearchProvidersRequestInterface;

\it('searches providers with basic authorization flow', function () {
    $providers = \client()->searchProviders([
        'authorization_flow' => [
            'provider_selection' => [
                'type' => 'user_selected'
            ]
        ]
    ]);

    \expect($providers)->toBeArray();
    \expect(\count($providers))->toBeGreaterThan(0);
    \expect($providers[0])->toBeInstanceOf(PaymentsProviderInterface::class);
    \expect($providers[0]->getId())->toBeString();
    \expect($providers[0]->getCapabilities())->toBeArray();
});

\it('searches providers using SearchProvidersRequest builder', function () {
    $searchRequest = \client()->searchProvidersRequest()
        ->authorizationFlow([
            'provider_selection' => [
                'type' => 'user_selected'
            ]
        ]);

    \expect($searchRequest)->toBeInstanceOf(SearchProvidersRequestInterface::class);

    $providers = \client()->searchProviders($searchRequest);

    \expect($providers)->toBeArray();
    \expect(\count($providers))->toBeGreaterThan(0);
    \expect($providers[0])->toBeInstanceOf(PaymentsProviderInterface::class);
    \expect($providers[0]->getId())->toBeString();
});

\it('searches providers filtered by country', function () {
    $providers = \client()->searchProviders([
        'authorization_flow' => [
            'provider_selection' => [
                'type' => 'user_selected'
            ]
        ],
        'countries' => ['GB']
    ]);

    \expect($providers)->toBeArray();
    \expect(\count($providers))->toBeGreaterThan(0);
    
    foreach ($providers as $provider) {
        \expect($provider)->toBeInstanceOf(PaymentsProviderInterface::class);
        \expect($provider->getId())->toBeString();
        \expect($provider->getCountryCode())->toBe('GB');
    }
});

\it('searches providers filtered by multiple countries', function () {
    $providers = \client()->searchProviders([
        'authorization_flow' => [
            'provider_selection' => [
                'type' => 'user_selected'
            ]
        ],
        'countries' => ['GB', 'DE']
    ]);

    \expect($providers)->toBeArray();
    \expect(\count($providers))->toBeGreaterThan(0);
    
    $countryCodes = [];
    foreach ($providers as $provider) {
        \expect($provider)->toBeInstanceOf(PaymentsProviderInterface::class);
        \expect($provider->getId())->toBeString();
        $countryCodes[] = $provider->getCountryCode();
    }
    
    $uniqueCountries = \array_unique($countryCodes);
    \expect($uniqueCountries)->toContain('GB');
});

\it('searches providers filtered by currency', function () {
    $providers = \client()->searchProviders([
        'authorization_flow' => [
            'provider_selection' => [
                'type' => 'user_selected'
            ]
        ],
        'currencies' => ['GBP']
    ]);

    \expect($providers)->toBeArray();
    \expect(\count($providers))->toBeGreaterThan(0);
    
    foreach ($providers as $provider) {
        \expect($provider)->toBeInstanceOf(PaymentsProviderInterface::class);
        \expect($provider->getId())->toBeString();
        \expect($provider->getCapabilities())->toBeArray();
    }
});

\it('searches providers filtered by customer segment', function () {
    $providers = \client()->searchProviders([
        'authorization_flow' => [
            'provider_selection' => [
                'type' => 'user_selected'
            ]
        ],
        'customer_segments' => ['retail']
    ]);

    \expect($providers)->toBeArray();
    \expect(\count($providers))->toBeGreaterThan(0);
    
    foreach ($providers as $provider) {
        \expect($provider)->toBeInstanceOf(PaymentsProviderInterface::class);
        \expect($provider->getId())->toBeString();
        \expect($provider->getCapabilities())->toBeArray();
    }
});

\it('searches providers with release channel filter', function () {
    $providers = \client()->searchProviders([
        'authorization_flow' => [
            'provider_selection' => [
                'type' => 'user_selected'
            ]
        ],
        'release_channel' => 'general_availability'
    ]);

    \expect($providers)->toBeArray();
    \expect(\count($providers))->toBeGreaterThan(0);
    
    foreach ($providers as $provider) {
        \expect($provider)->toBeInstanceOf(PaymentsProviderInterface::class);
        \expect($provider->getId())->toBeString();
        \expect($provider->getCapabilities())->toBeArray();
    }
});

\it('searches providers with comprehensive filters using builder', function () {
    $searchRequest = \client()->searchProvidersRequest()
        ->authorizationFlow([
            'provider_selection' => [
                'type' => 'user_selected'
            ]
        ])
        ->countries(['GB'])
        ->currencies(['GBP'])
        ->customerSegments(['retail'])
        ->releaseChannel('general_availability');

    $providers = \client()->searchProviders($searchRequest);

    \expect($providers)->toBeArray();
    \expect(\count($providers))->toBeGreaterThan(0);
    
    foreach ($providers as $provider) {
        \expect($provider)->toBeInstanceOf(PaymentsProviderInterface::class);
        \expect($provider->getId())->toBeString();
        \expect($provider->getDisplayName())->toBeString();
        \expect($provider->getCapabilities())->toBeArray();
        \expect($provider->getCountryCode())->toBe('GB');
        
        // Test optional fields
        if ($provider->getIconUri()) {
            \expect($provider->getIconUri())->toBeString();
        }
        if ($provider->getLogoUri()) {
            \expect($provider->getLogoUri())->toBeString();
        }
        if ($provider->getBgColor()) {
            \expect($provider->getBgColor())->toMatchRegExp('/^#[A-F0-9]{6}$/');
        }
        if ($provider->getSwiftCode()) {
            \expect($provider->getSwiftCode())->toBeString();
        }
        if ($provider->getBinRanges()) {
            \expect($provider->getBinRanges())->toBeArray();
        }
    }
});

\it('verifies provider builder methods return same instance', function () {
    $searchRequest = \client()->searchProvidersRequest();
    
    $result1 = $searchRequest->authorizationFlow(['provider_selection' => ['type' => 'user_selected']]);
    $result2 = $searchRequest->countries(['GB']);
    $result3 = $searchRequest->currencies(['GBP']);
    $result4 = $searchRequest->customerSegments(['retail']);
    $result5 = $searchRequest->releaseChannel('general_availability');
    
    \expect($result1)->toBe($searchRequest);
    \expect($result2)->toBe($searchRequest);
    \expect($result3)->toBe($searchRequest);
    \expect($result4)->toBe($searchRequest);
    \expect($result5)->toBe($searchRequest);
});

\it('verifies provider builder getters work correctly', function () {
    $authFlow = ['provider_selection' => ['type' => 'user_selected']];
    $countries = ['GB', 'DE'];
    $currencies = ['GBP', 'EUR'];
    $segments = ['retail', 'business'];
    $channel = 'general_availability';
    
    $searchRequest = \client()->searchProvidersRequest()
        ->authorizationFlow($authFlow)
        ->countries($countries)
        ->currencies($currencies)
        ->customerSegments($segments)
        ->releaseChannel($channel);
    
    \expect($searchRequest->getAuthorizationFlow())->toBe($authFlow);
    \expect($searchRequest->getCountries())->toBe($countries);
    \expect($searchRequest->getCurrencies())->toBe($currencies);
    \expect($searchRequest->getCustomerSegments())->toBe($segments);
    \expect($searchRequest->getReleaseChannel())->toBe($channel);
});