<?php

declare(strict_types=1);

use TrueLayer\Constants\AuthorizationFlowActionTypes;
use TrueLayer\Contracts\Beneficiary\BeneficiaryInterface;
use TrueLayer\Contracts\Payment\AuthorizationFlow\Action\ProviderSelectionActionInterface;
use TrueLayer\Contracts\Payment\AuthorizationFlow\Action\RedirectActionInterface;
use TrueLayer\Contracts\Payment\AuthorizationFlow\Action\WaitActionInterface;
use TrueLayer\Contracts\Payment\AuthorizationFlow\ConfigurationInterface;
use TrueLayer\Contracts\Payment\PaymentAuthorizationRequiredInterface;
use TrueLayer\Contracts\Payment\PaymentAuthorizedInterface;
use TrueLayer\Contracts\Payment\PaymentAuthorizingInterface;
use TrueLayer\Contracts\Payment\PaymentExecutedInterface;
use TrueLayer\Contracts\Payment\PaymentFailedInterface;
use TrueLayer\Contracts\Payment\PaymentRetrievedInterface;
use TrueLayer\Contracts\Payment\PaymentSettledInterface;
use TrueLayer\Contracts\ProviderInterface;
use TrueLayer\Contracts\UserInterface;
use TrueLayer\Tests\Mocks\PaymentResponse;

function assertCommon(PaymentRetrievedInterface $payment)
{
    expect($payment->getId())->toBeString();
    expect($payment->getStatus())->toBeString();
    expect($payment->getBeneficiary())->toBeInstanceOf(BeneficiaryInterface::class);
    expect($payment->getUser())->toBeInstanceOf(UserInterface::class);
    expect($payment->getCurrency())->toBeString();
    expect($payment->getAmountInMinor())->toBeInt();
    expect($payment->getCreatedAt())->toBeInstanceOf(DateTimeInterface::class);
}

\it('handles payment authorization required', function () {
    $payment = \sdk(PaymentResponse::authorizationRequired())->getPayment('1');

    \expect($payment)->toBeInstanceOf(PaymentAuthorizationRequiredInterface::class);
    \expect($payment->isAuthorizationRequired())->toBe(true);
    \expect($payment->isAuthorizing())->toBe(false);
    \expect($payment->isAuthorized())->toBe(false);
    \expect($payment->isExecuted())->toBe(false);
    \expect($payment->isSettled())->toBe(false);
    \expect($payment->isFailed())->toBe(false);

    assertCommon($payment);
});

\it('handles payment authorizing - provider selection', function () {
    /** @var PaymentAuthorizingInterface $payment */
    $payment = \sdk(PaymentResponse::authorizingProviderSelection())->getPayment('1');

    /** @var ProviderSelectionActionInterface $next */
    $next = $payment->getAuthorizationFlowNextAction();

    \expect($payment)->toBeInstanceOf(PaymentAuthorizingInterface::class);
    \expect($payment->isAuthorizationRequired())->toBe(false);
    \expect($payment->isAuthorizing())->toBe(true);
    \expect($payment->isAuthorized())->toBe(false);
    \expect($payment->isExecuted())->toBe(false);
    \expect($payment->isSettled())->toBe(false);
    \expect($payment->isFailed())->toBe(false);

    \expect($next)->toBeInstanceOf(ProviderSelectionActionInterface::class);
    \expect($next->getType())->toBe(AuthorizationFlowActionTypes::PROVIDER_SELECTION);
    \expect(\count($next->getProviders()) > 0)->toBe(true);

    \expect($next->getProviders()[0])->toBeInstanceOf(ProviderInterface::class);
    \expect($next->getProviders()[0]->getProviderId())->toBe('mock-payments-gb-redirect');
    \expect($next->getProviders()[0]->getDisplayName())->toBe('Mock UK Payments - Redirect Flow');
    \expect($next->getProviders()[0]->getIconUri())->toBe('https://truelayer-provider-assets.s3.amazonaws.com/uk/icons/mock-payments-gb-redirect.svg');
    \expect($next->getProviders()[0]->getLogoUri())->toBe('https://truelayer-provider-assets.s3.amazonaws.com/uk/logos/mock-payments-gb-redirect.svg');
    \expect($next->getProviders()[0]->getBgColor())->toBe('#FFFFFF');
    \expect($next->getProviders()[0]->getCountryCode())->toBe('GB');

    assertCommon($payment);
});

\it('handles payment authorization flow config', function () {
    /** @var PaymentAuthorizingInterface $payment */
    $payment = \sdk(PaymentResponse::authorizingProviderSelection())->getPayment('1');

    \expect($payment->getAuthorizationFlowConfig())->toBeInstanceOf(ConfigurationInterface::class);
    \expect($payment->getAuthorizationFlowConfig()->isRedirectSupported())->toBe(true);
    \expect($payment->getAuthorizationFlowConfig()->isProviderSelectionSupported())->toBe(true);
    \expect($payment->getAuthorizationFlowConfig()->getRedirectReturnUri())->toBe('https://penny.t7r.dev/redirect/v3');

    assertCommon($payment);
});

\it('handles payment authorizing - redirect', function () {
    /** @var PaymentAuthorizingInterface $payment */
    $payment = \sdk(PaymentResponse::authorizingRedirect())->getPayment('1');

    /** @var RedirectActionInterface $next */
    $next = $payment->getAuthorizationFlowNextAction();

    \expect($payment)->toBeInstanceOf(PaymentAuthorizingInterface::class);
    \expect($next)->toBeInstanceOf(RedirectActionInterface::class);
    \expect($next->getType())->toBe(AuthorizationFlowActionTypes::REDIRECT);
    \expect($next->getUri())->toBe('https://foo.com');
    \expect($next->getMetadataType())->toBe('provider');
    \expect($next->getProvider())->toBeNull();

    assertCommon($payment);
});

\it('handles payment authorizing - wait', function () {
    /** @var PaymentAuthorizingInterface $payment */
    $payment = \sdk(PaymentResponse::authorizingWait())->getPayment('1');

    /** @var WaitActionInterface $next */
    $next = $payment->getAuthorizationFlowNextAction();

    \expect($payment)->toBeInstanceOf(PaymentAuthorizingInterface::class);
    \expect($next)->toBeInstanceOf(WaitActionInterface::class);
    \expect($next->getType())->toBe(AuthorizationFlowActionTypes::WAIT);

    assertCommon($payment);
});

\it('handles payment authorized', function () {
    /** @var PaymentAuthorizedInterface $payment */
    $payment = \sdk(PaymentResponse::authorized())->getPayment('1');

    \expect($payment)->toBeInstanceOf(PaymentAuthorizedInterface::class);
    \expect($payment->getAuthorizationFlowConfig())->toBeInstanceOf(ConfigurationInterface::class);
    \expect($payment->isAuthorizationRequired())->toBe(false);
    \expect($payment->isAuthorizing())->toBe(false);
    \expect($payment->isAuthorized())->toBe(true);
    \expect($payment->isExecuted())->toBe(false);
    \expect($payment->isSettled())->toBe(false);
    \expect($payment->isFailed())->toBe(false);

    assertCommon($payment);
});

\it('handles payment executed', function () {
    /** @var PaymentExecutedInterface $payment */
    $payment = \sdk(PaymentResponse::executed())->getPayment('1');

    \expect($payment)->toBeInstanceOf(PaymentExecutedInterface::class);
    \expect($payment->getAuthorizationFlowConfig())->toBeInstanceOf(ConfigurationInterface::class);
    \expect($payment->isAuthorizationRequired())->toBe(false);
    \expect($payment->isAuthorizing())->toBe(false);
    \expect($payment->isAuthorized())->toBe(false);
    \expect($payment->isExecuted())->toBe(true);
    \expect($payment->isSettled())->toBe(false);
    \expect($payment->isFailed())->toBe(false);
    \expect($payment->getExecutedAt()->toIso8601ZuluString('microsecond'))->toBe('2022-01-13T22:13:09.914177Z');

    assertCommon($payment);
});

\it('handles payment settled', function () {
    /** @var PaymentSettledInterface $payment */
    $payment = \sdk(PaymentResponse::settled())->getPayment('1');

    \expect($payment)->toBeInstanceOf(PaymentSettledInterface::class);
    \expect($payment->getAuthorizationFlowConfig())->toBeInstanceOf(ConfigurationInterface::class);
    \expect($payment->isAuthorizationRequired())->toBe(false);
    \expect($payment->isAuthorizing())->toBe(false);
    \expect($payment->isAuthorized())->toBe(false);
    \expect($payment->isExecuted())->toBe(false);
    \expect($payment->isSettled())->toBe(true);
    \expect($payment->isFailed())->toBe(false);
    \expect($payment->getExecutedAt()->toIso8601ZuluString('microsecond'))->toBe('2022-01-13T22:13:09.914177Z');
    \expect($payment->getSettledAt()->toIso8601ZuluString('microsecond'))->toBe('2022-01-13T22:13:09.914177Z');

    assertCommon($payment);
});

\it('handles payment failed', function () {
    /** @var PaymentFailedInterface $payment */
    $payment = \sdk(PaymentResponse::failed())->getPayment('1');

    \expect($payment)->toBeInstanceOf(PaymentFailedInterface::class);
    \expect($payment->getAuthorizationFlowConfig())->toBeInstanceOf(ConfigurationInterface::class);
    \expect($payment->isAuthorizationRequired())->toBe(false);
    \expect($payment->isAuthorizing())->toBe(false);
    \expect($payment->isAuthorized())->toBe(false);
    \expect($payment->isExecuted())->toBe(false);
    \expect($payment->isSettled())->toBe(false);
    \expect($payment->isFailed())->toBe(true);
    \expect($payment->getFailedAt()->toIso8601ZuluString('microsecond'))->toBe('2022-01-13T20:22:25.645589Z');
    \expect($payment->getFailureStage())->toBe('authorizing');
    \expect($payment->getFailureReason())->toBe('authorization_failed');

    assertCommon($payment);
});

\it('handles payment with no auth flow config', function () {
    /** @var PaymentExecutedInterface $payment */
    $payment = \sdk(PaymentResponse::executedNoAuthFlowConfig())->getPayment('1');

    \expect($payment)->toBeInstanceOf(PaymentExecutedInterface::class);
    \expect($payment->getAuthorizationFlowConfig())->toBeNull();
});
