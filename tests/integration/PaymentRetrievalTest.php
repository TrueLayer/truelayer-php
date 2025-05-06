<?php

declare(strict_types=1);

use GuzzleHttp\Psr7\Response;
use TrueLayer\Constants\AuthorizationFlowActionTypes;
use TrueLayer\Constants\DateTime;
use TrueLayer\Interfaces\AccountIdentifier\BbanDetailsInterface;
use TrueLayer\Interfaces\AccountIdentifier\IbanDetailsInterface;
use TrueLayer\Interfaces\AccountIdentifier\NrbDetailsInterface;
use TrueLayer\Interfaces\AccountIdentifier\ScanDetailsInterface;
use TrueLayer\Interfaces\Payment\AuthorizationFlow\Action\ProviderSelectionActionInterface;
use TrueLayer\Interfaces\Payment\AuthorizationFlow\Action\RedirectActionInterface;
use TrueLayer\Interfaces\Payment\AuthorizationFlow\Action\WaitActionInterface;
use TrueLayer\Interfaces\Payment\AuthorizationFlow\ConfigurationInterface;
use TrueLayer\Interfaces\Payment\PaymentAttemptFailedInterface;
use TrueLayer\Interfaces\Payment\PaymentAuthorizationRequiredInterface;
use TrueLayer\Interfaces\Payment\PaymentAuthorizedInterface;
use TrueLayer\Interfaces\Payment\PaymentAuthorizingInterface;
use TrueLayer\Interfaces\Payment\PaymentExecutedInterface;
use TrueLayer\Interfaces\Payment\PaymentFailedInterface;
use TrueLayer\Interfaces\Payment\PaymentRetrievedInterface;
use TrueLayer\Interfaces\Payment\PaymentSettledInterface;
use TrueLayer\Interfaces\Payment\PaymentSourceInterface;
use TrueLayer\Interfaces\Payment\Scheme\InstantOnlySchemeSelectionInterface;
use TrueLayer\Interfaces\Payment\Scheme\InstantPreferredSchemeSelectionInterface;
use TrueLayer\Interfaces\Payment\Scheme\InstantSchemeSelectionInterface;
use TrueLayer\Interfaces\Payment\Scheme\UserSelectedSchemeSelectionInterface;
use TrueLayer\Interfaces\PaymentMethod\BankTransferPaymentMethodInterface;
use TrueLayer\Interfaces\Provider\ProviderInterface;
use TrueLayer\Interfaces\Provider\UserSelectedProviderSelectionInterface;
use TrueLayer\Tests\Integration\Mocks\PaymentResponse;

function assertPaymentCommon(PaymentRetrievedInterface $payment)
{
    \expect($payment->getId())->toBeString();
    \expect($payment->getStatus())->toBeString();
    \expect($payment->getUserId())->toBeString();
    \expect($payment->getCurrency())->toBeString();
    \expect($payment->getAmountInMinor())->toBeInt();
    \expect($payment->getCreatedAt())->toBeInstanceOf(DateTimeInterface::class);
}

\it('handles payment cancellation', function () {
    $client = \client([PaymentResponse::authorizationRequired(), PaymentResponse::cancelled(), PaymentResponse::failed()]);

    $payment = $client->getPayment('1');
    \assertPaymentCommon($payment);

    $cancelledPayment = $payment->cancel();

    \expect($cancelledPayment->isFailed())->toBeTrue();
});

\it('handles payment authorization required', function () {
    $payment = \client(PaymentResponse::authorizationRequired())->getPayment('1');

    \expect($payment)->toBeInstanceOf(PaymentAuthorizationRequiredInterface::class);
    \expect($payment->isAuthorizationRequired())->toBe(true);
    \expect($payment->isAuthorizing())->toBe(false);
    \expect($payment->isAuthorized())->toBe(false);
    \expect($payment->isExecuted())->toBe(false);
    \expect($payment->isSettled())->toBe(false);
    \expect($payment->isFailed())->toBe(false);

    \assertPaymentCommon($payment);
});

\it('handles payment authorizing - provider selection', function () {
    /** @var PaymentAuthorizingInterface $payment */
    $payment = \client(PaymentResponse::authorizingProviderSelection())->getPayment('1');

    /** @var ProviderSelectionActionInterface $next */
    $next = $payment->getAuthorizationFlowNextAction();

    \expect($payment)->toBeInstanceOf(PaymentAuthorizingInterface::class);
    \expect($payment->isAuthorizationRequired())->toBe(false);
    \expect($payment->isAuthorizing())->toBe(true);
    \expect($payment->isAuthorized())->toBe(false);
    \expect($payment->isExecuted())->toBe(false);
    \expect($payment->isSettled())->toBe(false);
    \expect($payment->isFailed())->toBe(false);
    \expect($payment->getMetadata())->toMatchArray([
        'metadata_key_1' => 'metadata_value_1',
        'metadata_key_2' => 'metadata_value_2',
        'metadata_key_3' => 'metadata_value_3',
    ]);

    \expect($next)->toBeInstanceOf(ProviderSelectionActionInterface::class);
    \expect($next->getType())->toBe(AuthorizationFlowActionTypes::PROVIDER_SELECTION);
    \expect(\count($next->getProviders()) > 0)->toBe(true);

    \expect($next->getProviders()[0])->toBeInstanceOf(ProviderInterface::class);
    \expect($next->getProviders()[0]->getId())->toBe('mock-payments-gb-redirect');
    \expect($next->getProviders()[0]->getDisplayName())->toBe('Mock UK Payments - Redirect Flow');
    \expect($next->getProviders()[0]->getIconUri())->toBe('https://truelayer-provider-assets.s3.amazonaws.com/uk/icons/mock-payments-gb-redirect.svg');
    \expect($next->getProviders()[0]->getLogoUri())->toBe('https://truelayer-provider-assets.s3.amazonaws.com/uk/logos/mock-payments-gb-redirect.svg');
    \expect($next->getProviders()[0]->getBgColor())->toBe('#FFFFFF');
    \expect($next->getProviders()[0]->getCountryCode())->toBe('GB');

    \assertPaymentCommon($payment);
});

\it('handles payment authorization flow config', function () {
    /** @var PaymentAuthorizingInterface $payment */
    $payment = \client(PaymentResponse::authorizingProviderSelection())->getPayment('1');

    \expect($payment->getAuthorizationFlowConfig())->toBeInstanceOf(ConfigurationInterface::class);
    \expect($payment->getAuthorizationFlowConfig()->getRedirectReturnUri())->toBe('https://penny.t7r.dev/redirect/v3');

    \assertPaymentCommon($payment);
});

\it('handles payment authorizing - redirect', function () {
    /** @var PaymentAuthorizingInterface $payment */
    $payment = \client(PaymentResponse::authorizingRedirect())->getPayment('1');

    /** @var RedirectActionInterface $next */
    $next = $payment->getAuthorizationFlowNextAction();

    \expect($payment)->toBeInstanceOf(PaymentAuthorizingInterface::class);
    \expect($payment->getMetadata())->toMatchArray([
        'metadata_key_1' => 'metadata_value_1',
        'metadata_key_2' => 'metadata_value_2',
        'metadata_key_3' => 'metadata_value_3',
    ]);
    \expect($next)->toBeInstanceOf(RedirectActionInterface::class);
    \expect($next->getType())->toBe(AuthorizationFlowActionTypes::REDIRECT);
    \expect($next->getUri())->toStartWith('https://');
    \expect($next->getMetadataType())->toBe('provider');
    \expect($next->getProvider())->toBeNull();

    \assertPaymentCommon($payment);
});

\it('handles payment authorizing - wait', function () {
    /** @var PaymentAuthorizingInterface $payment */
    $payment = \client(PaymentResponse::authorizingWait())->getPayment('1');

    /** @var WaitActionInterface $next */
    $next = $payment->getAuthorizationFlowNextAction();

    \expect($payment)->toBeInstanceOf(PaymentAuthorizingInterface::class);
    \expect($next)->toBeInstanceOf(WaitActionInterface::class);
    \expect($next->getType())->toBe(AuthorizationFlowActionTypes::WAIT);
    \expect($payment->getMetadata())->toMatchArray([
        'metadata_key_1' => 'metadata_value_1',
        'metadata_key_2' => 'metadata_value_2',
        'metadata_key_3' => 'metadata_value_3',
    ]);

    \assertPaymentCommon($payment);
});

\it('handles payment authorized', function () {
    /** @var PaymentAuthorizedInterface $payment */
    $payment = \client(PaymentResponse::authorized())->getPayment('1');

    \expect($payment)->toBeInstanceOf(PaymentAuthorizedInterface::class);
    \expect($payment->getAuthorizationFlowConfig())->toBeInstanceOf(ConfigurationInterface::class);
    \expect($payment->isAuthorizationRequired())->toBe(false);
    \expect($payment->isAuthorizing())->toBe(false);
    \expect($payment->isAuthorized())->toBe(true);
    \expect($payment->isExecuted())->toBe(false);
    \expect($payment->isSettled())->toBe(false);
    \expect($payment->isFailed())->toBe(false);
    \expect($payment->getMetadata())->toMatchArray([
        'metadata_key_1' => 'metadata_value_1',
        'metadata_key_2' => 'metadata_value_2',
        'metadata_key_3' => 'metadata_value_3',
    ]);

    \assertPaymentCommon($payment);
});

\it('handles payment executed', function () {
    /** @var PaymentExecutedInterface $payment */
    $payment = \client(PaymentResponse::executed())->getPayment('1');

    \expect($payment)->toBeInstanceOf(PaymentExecutedInterface::class);
    \expect($payment->getAuthorizationFlowConfig())->toBeInstanceOf(ConfigurationInterface::class);
    \expect($payment->isAuthorizationRequired())->toBe(false);
    \expect($payment->isAuthorizing())->toBe(false);
    \expect($payment->isAuthorized())->toBe(false);
    \expect($payment->isExecuted())->toBe(true);
    \expect($payment->isSettled())->toBe(false);
    \expect($payment->isFailed())->toBe(false);
    \expect($payment->getCreditableAt())->toBeNull();
    \expect($payment->getExecutedAt()->format(DateTime::FORMAT))->toBe('2022-02-04T14:12:07.705938Z');
    \expect($payment->getMetadata())->toMatchArray([
        'metadata_key_1' => 'metadata_value_1',
        'metadata_key_2' => 'metadata_value_2',
        'metadata_key_3' => 'metadata_value_3',
    ]);

    \assertPaymentCommon($payment);
});

\it('handles payment executed and creditable', function () {
    /** @var PaymentExecutedInterface $payment */
    $payment = \client(PaymentResponse::executedAndCreditable())->getPayment('1');

    \expect($payment)->toBeInstanceOf(PaymentExecutedInterface::class);
    \expect($payment->isExecuted())->toBe(true);
    \expect($payment->getCreditableAt()->format(DateTime::FORMAT))->toBe('2022-03-03T13:40:23.000000Z');

    \assertPaymentCommon($payment);
});

\it('handles payment settled', function () {
    /** @var PaymentSettledInterface $payment */
    $payment = \client(PaymentResponse::settled())->getPayment('1');
    $paymentSource = $payment->getPaymentSource();

    \expect($payment)->toBeInstanceOf(PaymentSettledInterface::class);
    \expect($payment->getAuthorizationFlowConfig())->toBeInstanceOf(ConfigurationInterface::class);
    \expect($payment->isAuthorizationRequired())->toBe(false);
    \expect($payment->isAuthorizing())->toBe(false);
    \expect($payment->isAuthorized())->toBe(false);
    \expect($payment->isExecuted())->toBe(false);
    \expect($payment->isSettled())->toBe(true);
    \expect($payment->isFailed())->toBe(false);
    \expect($payment->getExecutedAt()->format(DateTime::FORMAT))->toBe('2022-02-06T22:14:48.014149Z');
    \expect($payment->getSettledAt()->format(DateTime::FORMAT))->toBe('2022-02-06T22:14:51.382114Z');
    \expect($payment->getPaymentSource())->toBeInstanceOf(PaymentSourceInterface::class);
    \expect($payment->getMetadata())->toBeArray();
    \expect($payment->getMetadata())->toMatchArray([
        'metadata_key_1' => 'metadata_value_1',
        'metadata_key_2' => 'metadata_value_2',
        'metadata_key_3' => 'metadata_value_3',
    ]);

    \expect($paymentSource->getId())->toBeString();
    \expect($paymentSource->getAccountHolderName())->toBe('Bob');

    /** @var ScanDetailsInterface $scan */
    $scan = $paymentSource->getAccountIdentifiers()[0];
    \expect($scan)->toBeInstanceOf(ScanDetailsInterface::class);
    \expect($scan->getAccountNumber())->toBe('00002723');
    \expect($scan->getSortCode())->toBe('040662');

    /** @var IbanDetailsInterface $iban */
    $iban = $paymentSource->getAccountIdentifiers()[1];
    \expect($iban)->toBeInstanceOf(IbanDetailsInterface::class);
    \expect($iban->getIban())->toBe('GB53CLRB04066200002723');

    /** @var BbanDetailsInterface $bban */
    $bban = $paymentSource->getAccountIdentifiers()[2];
    \expect($bban)->toBeInstanceOf(BbanDetailsInterface::class);
    \expect($bban->getBban())->toBe('CLRB04066200002723');

    /** @var NrbDetailsInterface $nrb */
    $nrb = $paymentSource->getAccountIdentifiers()[3];
    \expect($nrb)->toBeInstanceOf(NrbDetailsInterface::class);
    \expect($nrb->getNrb())->toBe('61109010140000071219812874');

    \assertPaymentCommon($payment);
});

\it('handles payment settled with no payment source fields', function () {
    /** @var PaymentSettledInterface $payment */
    $payment = \client(PaymentResponse::settledNoPaymentSourceFields())->getPayment('1');
    $paymentSource = $payment->getPaymentSource();

    \expect($paymentSource->getAccountHolderName())->toBeNull();
    \expect($paymentSource->getAccountIdentifiers())->toBeEmpty();
});

\it('handles payment failed', function () {
    /** @var PaymentFailedInterface $payment */
    $payment = \client(PaymentResponse::failed())->getPayment('1');

    \expect($payment)->toBeInstanceOf(PaymentFailedInterface::class);
    \expect($payment->getAuthorizationFlowConfig())->toBeInstanceOf(ConfigurationInterface::class);
    \expect($payment->isAuthorizationRequired())->toBe(false);
    \expect($payment->isAuthorizing())->toBe(false);
    \expect($payment->isAuthorized())->toBe(false);
    \expect($payment->isExecuted())->toBe(false);
    \expect($payment->isSettled())->toBe(false);
    \expect($payment->isFailed())->toBe(true);
    \expect($payment->isAttemptFailed())->toBe(false);
    \expect($payment->getFailedAt()->format(DateTime::FORMAT))->toBe('2022-02-06T22:26:48.849469Z');
    \expect($payment->getFailureStage())->toBe('authorizing');
    \expect($payment->getFailureReason())->toBe('authorization_failed');
    \expect($payment->getMetadata())->toMatchArray([
        'metadata_key_1' => 'metadata_value_1',
        'metadata_key_2' => 'metadata_value_2',
        'metadata_key_3' => 'metadata_value_3',
    ]);

    \assertPaymentCommon($payment);
});

\it('handles payment attempt failed', function () {
    /** @var PaymentAttemptFailedInterface $payment */
    $payment = \client(PaymentResponse::attemptFailed())->getPayment('1');

    \expect($payment)->toBeInstanceOf(PaymentAttemptFailedInterface::class);
    \expect($payment->getAuthorizationFlowConfig())->toBeInstanceOf(ConfigurationInterface::class);
    \expect($payment->isAuthorizationRequired())->toBe(false);
    \expect($payment->isAuthorizing())->toBe(false);
    \expect($payment->isAuthorized())->toBe(false);
    \expect($payment->isExecuted())->toBe(false);
    \expect($payment->isSettled())->toBe(false);
    \expect($payment->isFailed())->toBe(false);
    \expect($payment->isAttemptFailed())->toBe(true);
    \expect($payment->getFailedAt()->format(DateTime::FORMAT))->toBe('2022-02-06T22:26:48.849469Z');
    \expect($payment->getFailureStage())->toBe('authorizing');
    \expect($payment->getFailureReason())->toBe('authorization_failed');

    \assertPaymentCommon($payment);
});

\it('handles payment with no auth flow config', function () {
    /** @var PaymentExecutedInterface $payment */
    $payment = \client(PaymentResponse::executedNoAuthFlow())->getPayment('1');

    \expect($payment)->toBeInstanceOf(PaymentExecutedInterface::class);
    \expect($payment->getAuthorizationFlowConfig())->toBeNull();
});

\it('handles payment with scheme selection', function (Response $mockResponse, string $expectedType, string $expectedTypeValue, ?bool $expectedAllowedRemitterFee = null) {
    /** @var BankTransferPaymentMethodInterface $method */
    $method = \client($mockResponse)->getPayment('1')->getPaymentMethod();

    /** @var UserSelectedProviderSelectionInterface $selection */
    $providerSelection = $method->getProviderSelection();
    $schemeSelection = $providerSelection->getSchemeSelection();

    \expect($schemeSelection)->toBeInstanceOf($expectedType);
    \expect($schemeSelection->getType())->toBe($expectedTypeValue);

    if ($expectedAllowedRemitterFee !== null) {
        /** @var InstantSchemeSelectionInterface $selection */
        $selection = $schemeSelection;
        \expect($selection->getAllowRemitterFee())->toBe($expectedAllowedRemitterFee);
    }
})->with([
    'user selected' => [
        'response' => PaymentResponse::executedWithUserSelectedSchemeSelection(),
        'expectedType' => UserSelectedSchemeSelectionInterface::class,
        'expectedTypeValue' => 'user_selected',
    ],
    'instant only' => [
        'response' => PaymentResponse::executedWithInstantOnlySchemeSelection(),
        'expectedType' => InstantOnlySchemeSelectionInterface::class,
        'expectedTypeValue' => 'instant_only',
        'expectedAllowedRemitterFee' => true,
    ],
    'instant preferred' => [
        'response' => PaymentResponse::executedWithInstantPreferredSchemeSelection(),
        'expectedType' => InstantPreferredSchemeSelectionInterface::class,
        'expectedTypeValue' => 'instant_preferred',
        'expectedAllowedRemitterFee' => true,
    ],
]);

\it('handles retry field correctly - empty object serialisation', function () {
    $payment1 = \client(PaymentResponse::authorizationRequiredWithRetryField())->getPayment('1');
    /** @var BankTransferPaymentMethodInterface $method */
    $method1 = $payment1->getPaymentMethod();

    $payment2 = \client(PaymentResponse::authorizationRequired())->getPayment('1');
    /** @var BankTransferPaymentMethodInterface $method */
    $method2 = $payment2->getPaymentMethod();

    \expect($method1->isPaymentRetryEnabled())->toBe(true);
    \expect($method2->isPaymentRetryEnabled())->toBe(false);
});
