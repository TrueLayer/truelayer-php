<?php

declare(strict_types=1);

use TrueLayer\Constants\AuthorizationFlowActionTypes;
use TrueLayer\Constants\DateTime;
use TrueLayer\Interfaces\Beneficiary\BeneficiaryInterface;
use TrueLayer\Interfaces\Payment\AuthorizationFlow\Action\ProviderSelectionActionInterface;
use TrueLayer\Interfaces\Payment\AuthorizationFlow\Action\RedirectActionInterface;
use TrueLayer\Interfaces\Payment\AuthorizationFlow\Action\WaitActionInterface;
use TrueLayer\Interfaces\Payment\AuthorizationFlow\ConfigurationInterface;
use TrueLayer\Interfaces\Payment\PaymentAuthorizationRequiredInterface;
use TrueLayer\Interfaces\Payment\PaymentAuthorizedInterface;
use TrueLayer\Interfaces\Payment\PaymentAuthorizingInterface;
use TrueLayer\Interfaces\Payment\PaymentExecutedInterface;
use TrueLayer\Interfaces\Payment\PaymentFailedInterface;
use TrueLayer\Interfaces\Payment\PaymentRetrievedInterface;
use TrueLayer\Interfaces\Payment\PaymentSettledInterface;
use TrueLayer\Interfaces\Payment\PaymentSourceInterface;
use TrueLayer\Interfaces\Provider\ProviderInterface;
use TrueLayer\Interfaces\AccountIdentifier\BbanDetailsInterface;
use TrueLayer\Interfaces\AccountIdentifier\IbanDetailsInterface;
use TrueLayer\Interfaces\AccountIdentifier\NrbDetailsInterface;
use TrueLayer\Interfaces\AccountIdentifier\ScanDetailsInterface;
use TrueLayer\Interfaces\UserInterface;
use TrueLayer\Tests\Mocks\PaymentResponse;

function assertCommon(PaymentRetrievedInterface $payment)
{
    \expect($payment->getId())->toBeString();
    \expect($payment->getStatus())->toBeString();
    \expect($payment->getBeneficiary())->toBeInstanceOf(BeneficiaryInterface::class);
    \expect($payment->getUser())->toBeInstanceOf(UserInterface::class);
    \expect($payment->getCurrency())->toBeString();
    \expect($payment->getAmountInMinor())->toBeInt();
    \expect($payment->getCreatedAt())->toBeInstanceOf(DateTimeInterface::class);
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

    \assertCommon($payment);
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

    \assertCommon($payment);
});

\it('handles payment authorization flow config', function () {
    /** @var PaymentAuthorizingInterface $payment */
    $payment = \sdk(PaymentResponse::authorizingProviderSelection())->getPayment('1');

    \expect($payment->getAuthorizationFlowConfig())->toBeInstanceOf(ConfigurationInterface::class);
    \expect($payment->getAuthorizationFlowConfig()->isRedirectSupported())->toBe(true);
    \expect($payment->getAuthorizationFlowConfig()->isProviderSelectionSupported())->toBe(true);
    \expect($payment->getAuthorizationFlowConfig()->getRedirectReturnUri())->toBe('https://penny.t7r.dev/redirect/v3');

    \assertCommon($payment);
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

    \assertCommon($payment);
});

\it('handles payment authorizing - wait', function () {
    /** @var PaymentAuthorizingInterface $payment */
    $payment = \sdk(PaymentResponse::authorizingWait())->getPayment('1');

    /** @var WaitActionInterface $next */
    $next = $payment->getAuthorizationFlowNextAction();

    \expect($payment)->toBeInstanceOf(PaymentAuthorizingInterface::class);
    \expect($next)->toBeInstanceOf(WaitActionInterface::class);
    \expect($next->getType())->toBe(AuthorizationFlowActionTypes::WAIT);

    \assertCommon($payment);
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

    \assertCommon($payment);
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
    \expect($payment->getExecutedAt()->format(DateTime::FORMAT))->toBe('2022-01-13T22:13:09.914177Z');

    \expect($payment->getPaymentSource()->getExternalAccountId())->toBeNull();
    \expect($payment->getPaymentSource()->getAccountHolderName())->toBeNull();
    \expect($payment->getPaymentSource()->getAccountIdentifiers())->toBeArray();
    \expect($payment->getPaymentSource()->getAccountIdentifiers())->toBeEmpty();

    \assertCommon($payment);
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
    \expect($payment->getExecutedAt()->format(DateTime::FORMAT))->toBe('2022-01-13T22:13:09.914177Z');
    \expect($payment->getSettledAt()->format(DateTime::FORMAT))->toBe('2022-01-13T22:13:09.914177Z');
    \expect($payment->getPaymentSource())->toBeInstanceOf(PaymentSourceInterface::class);

    \expect($payment->getPaymentSource()->getExternalAccountId())->toBeNull();
    \expect($payment->getPaymentSource()->getAccountHolderName())->toBeNull();
    \expect($payment->getPaymentSource()->getAccountIdentifiers())->toBeArray();
    \expect($payment->getPaymentSource()->getAccountIdentifiers())->toBeEmpty();

    \assertCommon($payment);
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
    \expect($payment->getFailedAt()->format(DateTime::FORMAT))->toBe('2022-01-13T20:22:25.645589Z');
    \expect($payment->getFailureStage())->toBe('authorizing');
    \expect($payment->getFailureReason())->toBe('authorization_failed');

    \assertCommon($payment);
});

\it('handles payment with no auth flow config', function () {
    /** @var PaymentExecutedInterface $payment */
    $payment = \sdk(PaymentResponse::executedNoAuthFlowConfig())->getPayment('1');

    \expect($payment)->toBeInstanceOf(PaymentExecutedInterface::class);
    \expect($payment->getAuthorizationFlowConfig())->toBeNull();
});

\it('handles payment source of funds', function () {
    /** @var PaymentExecutedInterface $payment */
    $payment = \sdk(PaymentResponse::executedWithPaymentSourceData())->getPayment('1');

    $paymentSource = $payment->getPaymentSource();
    \expect($paymentSource->getAccountHolderName())->toBe('John Doe');
    \expect($paymentSource->getExternalAccountId())->toBe('123');

    /** @var ScanDetailsInterface $scan */
    $scan = $paymentSource->getAccountIdentifiers()[0];
    \expect($scan)->toBeInstanceOf(ScanDetailsInterface::class);
    \expect($scan->getAccountNumber())->toBe('12345678');
    \expect($scan->getSortCode())->toBe('010203');

    /** @var IbanDetailsInterface $iban */
    $iban = $paymentSource->getAccountIdentifiers()[1];
    \expect($iban)->toBeInstanceOf(IbanDetailsInterface::class);
    \expect($iban->getIban())->toBe('AT483200000012345864');

    /** @var BbanDetailsInterface $bban */
    $bban = $paymentSource->getAccountIdentifiers()[2];
    \expect($bban)->toBeInstanceOf(BbanDetailsInterface::class);
    \expect($bban->getBban())->toBe('539007547034');

    /** @var NrbDetailsInterface $nrb */
    $nrb = $paymentSource->getAccountIdentifiers()[3];
    \expect($nrb)->toBeInstanceOf(NrbDetailsInterface::class);
    \expect($nrb->getNrb())->toBe('61109010140000071219812874');
});
