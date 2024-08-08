<?php

declare(strict_types=1);

use TrueLayer\Constants\AuthorizationFlowActionTypes;
use TrueLayer\Constants\AuthorizationFlowStatusTypes;
use TrueLayer\Constants\Endpoints;
use TrueLayer\Constants\FormInputTypes;
use TrueLayer\Constants\SchemeSelectionTypes;
use TrueLayer\Interfaces\Payment\AuthorizationFlow\Action\ProviderSelectionActionInterface;
use TrueLayer\Interfaces\Payment\AuthorizationFlow\Action\RedirectActionInterface;
use TrueLayer\Interfaces\Payment\AuthorizationFlow\ConfigurationInterface;
use TrueLayer\Interfaces\Payment\PaymentAuthorizingInterface;
use TrueLayer\Interfaces\Payment\PaymentCreatedInterface;
use TrueLayer\Interfaces\Payment\PaymentExecutedInterface;
use TrueLayer\Interfaces\Payment\PaymentFailedInterface;
use TrueLayer\Interfaces\Provider\ProviderInterface;

\it('creates an IBAN payment', function () {
    $helper = \paymentHelper();
    $created = $helper->create(
        $helper->bankTransferMethod($helper->ibanBeneficiary()), $helper->user(), 'EUR'
    );

    \expect($created)->toBeInstanceOf(PaymentCreatedInterface::class);
    \expect($created->getId())->toBeString();
    \expect($created->getResourceToken())->toBeString();
    \expect($created->getUserId())->toBeString();
    \expect($created->getDetails()->getMetadata())->toMatchArray([
        'metadata_key_1' => 'metadata_value_1',
        'metadata_key_2' => 'metadata_value_2',
        'metadata_key_3' => 'metadata_value_3',
    ]);
});

\it('creates a SCAN payment', function () {
    $created = \paymentHelper()->create();

    \expect($created)->toBeInstanceOf(PaymentCreatedInterface::class);
    \expect($created->getId())->toBeString();
    \expect($created->getResourceToken())->toBeString();
    \expect($created->getUserId())->toBeString();
    \expect($created->getDetails()->getMetadata())->toMatchArray([
        'metadata_key_1' => 'metadata_value_1',
        'metadata_key_2' => 'metadata_value_2',
        'metadata_key_3' => 'metadata_value_3',
    ]);

    return $created;
});

\it('creates a payment with a preselected provider', function () {
    $helper = \paymentHelper();

    $paymentMethod = $helper->bankTransferMethod($helper->sortCodeBeneficiary())
        ->providerSelection(
            $helper->providerSelectionPreselected()
                ->schemeSelection($helper->schemeSelection(SchemeSelectionTypes::PRESELECTED))
        );

    $created = $helper->create(
        $paymentMethod, $helper->user()
    );

    \expect($created)->toBeInstanceOf(PaymentCreatedInterface::class);
    \expect($created->getId())->toBeString();
    \expect($created->getResourceToken())->toBeString();
    \expect($created->getUserId())->toBeString();
    \expect($created->getDetails()->getMetadata())->toMatchArray([
        'metadata_key_1' => 'metadata_value_1',
        'metadata_key_2' => 'metadata_value_2',
        'metadata_key_3' => 'metadata_value_3',
    ]);
});

\it('creates a payment with a preselected provider and remitter set', function () {
    $helper = \paymentHelper();

    $providerSelection = $helper->providerSelectionPreselected()
        ->remitter($helper->remitter())
        ->schemeSelection($helper->schemeSelection(SchemeSelectionTypes::PRESELECTED));

    $paymentMethod = $helper->bankTransferMethod($helper->sortCodeBeneficiary())
        ->providerSelection($providerSelection);

    $created = $helper->create(
        $paymentMethod, $helper->user()
    );

    \expect($created)->toBeInstanceOf(PaymentCreatedInterface::class);
    \expect($created->getId())->toBeString();
    \expect($created->getResourceToken())->toBeString();
    \expect($created->getUserId())->toBeString();
    \expect($created->getDetails()->getMetadata())->toMatchArray([
        'metadata_key_1' => 'metadata_value_1',
        'metadata_key_2' => 'metadata_value_2',
        'metadata_key_3' => 'metadata_value_3',
    ]);
});

\it('creates a payment with a preselected provider and preselected scheme', function () {
    $helper = \paymentHelper();

    $schemeSelection = $helper->schemeSelection(SchemeSelectionTypes::PRESELECTED);

    $providerSelection = $helper->providerSelectionPreselected()
        ->schemeSelection($schemeSelection);

    $paymentMethod = $helper->bankTransferMethod($helper->sortCodeBeneficiary())
        ->providerSelection($providerSelection);

    $created = $helper->create(
        $paymentMethod, $helper->user()
    );

    \expect($created)->toBeInstanceOf(PaymentCreatedInterface::class);
    \expect($created->getId())->toBeString();
    \expect($created->getResourceToken())->toBeString();
    \expect($created->getUserId())->toBeString();
    \expect($created->getDetails()->getMetadata())->toMatchArray([
        'metadata_key_1' => 'metadata_value_1',
        'metadata_key_2' => 'metadata_value_2',
        'metadata_key_3' => 'metadata_value_3',
    ]);
});

\it('starts payment authorization - deprecated method', function () {
    $created = \paymentHelper()->create();

    $response = \client()->startPaymentAuthorization($created, 'https://console.truelayer.com/redirect-page');

    /** @var ProviderSelectionActionInterface $next */
    $next = $response->getNextAction();

    \expect($response->getStatus())->toBe(AuthorizationFlowStatusTypes::AUTHORIZING);
    \expect($next)->toBeInstanceOf(ProviderSelectionActionInterface::class);
    \expect($next->getType())->toBe(AuthorizationFlowActionTypes::PROVIDER_SELECTION);
    \expect($next->getProviders()[0])->toBeInstanceOf(ProviderInterface::class);

    $config = \getAuthorizationFlowConfig($created->getId());
    \expect($config)->not->toHaveKeys(['form', 'scheme_selection', 'user_account_selection']);
    \expect($config['redirect']['return_uri'])->toBe('https://console.truelayer.com/redirect-page');

    return $created;
});

\it('starts payment authorization', function () {
    $created = \paymentHelper()->create();

    $response = \client()->paymentAuthorizationFlow($created)
        ->returnUri('https://console.truelayer.com/redirect-page')
        ->enableProviderSelection()
        ->start();

    /** @var ProviderSelectionActionInterface $next */
    $next = $response->getNextAction();

    \expect($response->getStatus())->toBe(AuthorizationFlowStatusTypes::AUTHORIZING);
    \expect($next)->toBeInstanceOf(ProviderSelectionActionInterface::class);
    \expect($next->getType())->toBe(AuthorizationFlowActionTypes::PROVIDER_SELECTION);
    \expect($next->getProviders()[0])->toBeInstanceOf(ProviderInterface::class);

    $config = \getAuthorizationFlowConfig($created->getId());
    \expect($config)->not->toHaveKeys(['scheme_selection', 'user_account_selection']);
    \expect($config['redirect']['return_uri'])->toBe('https://console.truelayer.com/redirect-page');
    \expect($config['form']['input_types'])->toBeEmpty();

    return $created;
});

\it('starts payment authorization with hpp capabilities', function () {
    $created = \paymentHelper()->create();

    $created->authorizationFlow()
        ->returnUri('https://console.truelayer.com/redirect-page')
        ->useHPPCapabilities()
        ->start();

    $config = \getAuthorizationFlowConfig($created->getId());

    \expect($config)->not->toHaveKey('user_account_selection');
    \expect($config['provider_selection'])->toBeArray();
    \expect($config['scheme_selection'])->toBeArray();
    \expect($config['redirect']['return_uri'])->toBe('https://console.truelayer.com/redirect-page');
    \expect($config['redirect'])->not->toHaveKey('direct_return_uri');
    \expect($config['form']['input_types'])->toContain(FormInputTypes::SELECT, FormInputTypes::TEXT, FormInputTypes::TEXT_WITH_IMAGE);
});

\it('starts payment authorization with all capabilities', function () {
    $created = \paymentHelper()->create();

    $created->authorizationFlow()
        ->directReturnUri('https://console.truelayer.com/direct-return-page')
        ->returnUri('https://console.truelayer.com/redirect-page')
        ->enableProviderSelection()
        ->enableSchemeSelection()
        ->enableUserAccountSelection()
        ->formInputTypes([FormInputTypes::TEXT])
        ->start();

    $config = \getAuthorizationFlowConfig($created->getId());

    \expect($config['user_account_selection'])->toBeArray();
    \expect($config['provider_selection'])->toBeArray();
    \expect($config['scheme_selection'])->toBeArray();
    \expect($config['redirect']['return_uri'])->toBe('https://console.truelayer.com/redirect-page');
    \expect($config['redirect']['direct_return_uri'])->toBe('https://console.truelayer.com/direct-return-page');
    \expect($config['form']['input_types'])->toContain(FormInputTypes::TEXT);
});

\it('retrieves payment as authorizing - provider selection', function (PaymentCreatedInterface $created) {
    /** @var PaymentAuthorizingInterface $payment */
    $payment = $created->getDetails();

    \expect($payment)->toBeInstanceOf(PaymentAuthorizingInterface::class);
    \expect($payment->getAuthorizationFlowNextAction())->toBeInstanceOf(ProviderSelectionActionInterface::class);
    \expect($payment->getAuthorizationFlowConfig())->toBeInstanceOf(ConfigurationInterface::class);
    \expect($payment->getMetadata())->toMatchArray([
        'metadata_key_1' => 'metadata_value_1',
        'metadata_key_2' => 'metadata_value_2',
        'metadata_key_3' => 'metadata_value_3',
    ]);

    return $created;
})->depends('it starts payment authorization');

\it('submits provider', function (PaymentCreatedInterface $created) {
    $response = \client()->submitPaymentProvider($created, 'mock-payments-gb-redirect');

    /** @var RedirectActionInterface $next */
    $next = $response->getNextAction();

    \expect($response->getStatus())->toBe(AuthorizationFlowStatusTypes::AUTHORIZING);
    \expect($next)->toBeInstanceOf(RedirectActionInterface::class);
    \expect($next->getType())->toBe(AuthorizationFlowActionTypes::REDIRECT);
    \expect($next->getUri())->toBeString();

    return $created;
})->depends('it starts payment authorization');

\it('retrieves payment as authorizing - redirect', function (PaymentCreatedInterface $created) {
    /** @var PaymentAuthorizingInterface $payment */
    $payment = $created->getDetails();

    \expect($payment)->toBeInstanceOf(PaymentAuthorizingInterface::class);
    \expect($payment->getAuthorizationFlowNextAction())->toBeInstanceOf(RedirectActionInterface::class);
    \expect($payment->getAuthorizationFlowConfig())->toBeInstanceOf(ConfigurationInterface::class);
    \expect($payment->getMetadata())->toMatchArray([
        'metadata_key_1' => 'metadata_value_1',
        'metadata_key_2' => 'metadata_value_2',
        'metadata_key_3' => 'metadata_value_3',
    ]);

    return $created;
})->depends('it submits provider');

\it('executes payment', function (PaymentCreatedInterface $created) {
    /** @var RedirectActionInterface $next */
    $next = $created->getDetails()->getAuthorizationFlowNextAction();

    \bankAction($next->getUri(), 'Execute');
    \sleep(10);

    /* @var PaymentExecutedInterface $payment */
    $payment = $created->getDetails();

    \expect($payment)->toBeInstanceOf(PaymentExecutedInterface::class);
    \expect($payment->getAuthorizationFlowConfig())->toBeInstanceOf(ConfigurationInterface::class);
    \expect($payment->getAuthorizationFlowConfig()->getRedirectReturnUri())->toBeString();
    \expect($payment->getExecutedAt())->toBeInstanceOf(DateTimeInterface::class);

    return $created;
})->depends('it submits provider');

\it('creates payment and fails authorization', function () {
    $created = \paymentHelper()->create();
    $created->startAuthorization('https://console.truelayer.com/redirect-page');
    \client()->submitPaymentProvider($created, 'mock-payments-gb-redirect');

    /** @var RedirectActionInterface $next */
    $next = $created->getDetails()->getAuthorizationFlowNextAction();

    \bankAction($next->getUri(), 'RejectExecution');
    \sleep(10);

    /** @var PaymentFailedInterface $payment */
    $payment = $created->getDetails();

    \expect($payment)->toBeInstanceOf(PaymentFailedInterface::class);
    \expect($payment->getFailedAt())->toBeInstanceOf(DateTimeInterface::class);
    \expect($payment->getFailureStage())->toBeString();
    \expect($payment->getFailureReason())->toBeString();
});

/**
 * Get the authorization flow config by calling the api directly as we don't yet have the config object mapped in the lib.
 *
 * @param string $paymentId
 *
 * @throws \TrueLayer\Exceptions\ApiRequestJsonSerializationException
 * @throws \TrueLayer\Exceptions\ApiResponseUnsuccessfulException
 * @throws \TrueLayer\Exceptions\InvalidArgumentException
 * @throws \TrueLayer\Exceptions\SignerException
 *
 * @return array
 */
function getAuthorizationFlowConfig(string $paymentId): array
{
    $paymentData = \client()->getApiClient()->request()
        ->uri(\str_replace('{id}', $paymentId, Endpoints::PAYMENTS_RETRIEVE))
        ->get();

    return $paymentData['authorization_flow']['configuration'];
}
