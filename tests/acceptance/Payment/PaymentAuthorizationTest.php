<?php

declare(strict_types=1);

use TrueLayer\Constants\AuthorizationFlowActionTypes;
use TrueLayer\Constants\AuthorizationFlowStatusTypes;
use TrueLayer\Interfaces\Payment\AuthorizationFlow\Action\ProviderSelectionActionInterface;
use TrueLayer\Interfaces\Payment\AuthorizationFlow\Action\RedirectActionInterface;
use TrueLayer\Interfaces\Payment\AuthorizationFlow\ConfigurationInterface;
use TrueLayer\Interfaces\Payment\PaymentAuthorizedInterface;
use TrueLayer\Interfaces\Payment\PaymentAuthorizingInterface;
use TrueLayer\Interfaces\Payment\PaymentCreatedInterface;
use TrueLayer\Interfaces\Payment\PaymentExecutedInterface;
use TrueLayer\Interfaces\Provider\ProviderInterface;

\it('creates a payment', function () {
    $created = \paymentHelper()->create();

    \expect($created)->toBeInstanceOf(PaymentCreatedInterface::class);
    \expect($created->getId())->toBeString();
    \expect($created->getResourceToken())->toBeString();
    \expect($created->getUserId())->toBeString();

    return $created;
});

\it('starts payment authorization', function (PaymentCreatedInterface $created) {
    $response = \sdk()->startPaymentAuthorization($created, 'https://penny.t7r.dev/redirect/v3');

    /** @var ProviderSelectionActionInterface $next */
    $next = $response->getNextAction();

    \expect($response->getStatus())->toBe(AuthorizationFlowStatusTypes::AUTHORIZING);
    \expect($next)->toBeInstanceOf(ProviderSelectionActionInterface::class);
    \expect($next->getType())->toBe(AuthorizationFlowActionTypes::PROVIDER_SELECTION);
    \expect($next->getProviders()[0])->toBeInstanceOf(ProviderInterface::class);

    return $created;
})->depends('it creates a payment');

\it('retrieves payment as authorizing - provider selection', function (PaymentCreatedInterface $created) {
    /** @var PaymentAuthorizingInterface $payment */
    $payment = $created->getDetails();

    \expect($payment)->toBeInstanceOf(PaymentAuthorizingInterface::class);
    \expect($payment->getAuthorizationFlowNextAction())->toBeInstanceOf(ProviderSelectionActionInterface::class);
    \expect($payment->getAuthorizationFlowConfig())->toBeInstanceOf(ConfigurationInterface::class);

    return $created;
})->depends('it starts payment authorization');

\it('submits provider', function (PaymentCreatedInterface $created) {
    $response = \sdk()->submitPaymentProvider($created, 'mock-payments-gb-redirect');

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

    return $created;
})->depends('it submits provider');

\it('authorizes payment', function (PaymentCreatedInterface $created) {
    /** @var RedirectActionInterface $next */
    $next = $created->getDetails()->getAuthorizationFlowNextAction();

    \bankAction($next->getUri(), 'Execute');

    /* @var PaymentAuthorizedInterface $payment */
    \sleep(3);
    $payment = $created->getDetails();

    \expect($payment)->toBeInstanceOf(PaymentAuthorizedInterface::class);
    \expect($payment->getAuthorizationFlowConfig())->toBeInstanceOf(ConfigurationInterface::class);
    \expect($payment->getAuthorizationFlowConfig()->getRedirectReturnUri())->toBeString();

    return $created;
})->depends('it submits provider');

\it('it retrieves executed payment', function (PaymentCreatedInterface $created) {
    \sleep(3);

    /** @var PaymentExecutedInterface $payment */
    $payment = $created->getDetails();

    \expect($payment)->toBeInstanceOf(PaymentExecutedInterface::class);
    \expect($payment->getExecutedAt())->toBeInstanceOf(DateTimeInterface::class);
})->depends('it authorizes payment');
