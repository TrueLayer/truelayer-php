<?php

declare(strict_types=1);

use Illuminate\Support\Arr;
use TrueLayer\Constants\AuthorizationFlowActionTypes;
use TrueLayer\Constants\AuthorizationFlowStatusTypes;
use TrueLayer\Interfaces\MerchantAccount\MerchantAccountInterface;
use TrueLayer\Interfaces\Payment\AuthorizationFlow\Action\ProviderSelectionActionInterface;
use TrueLayer\Interfaces\Payment\AuthorizationFlow\Action\RedirectActionInterface;
use TrueLayer\Interfaces\Payment\AuthorizationFlow\ConfigurationInterface;
use TrueLayer\Interfaces\Payment\PaymentAuthorizingInterface;
use TrueLayer\Interfaces\Payment\PaymentCreatedInterface;
use TrueLayer\Interfaces\Payment\PaymentSettledInterface;
use TrueLayer\Interfaces\Payment\PaymentSourceInterface;
use TrueLayer\Interfaces\Provider\ProviderInterface;

\it('creates a merchant payment', function () {
    $helper = \paymentHelper();

    $account = Arr::first(
        $helper->sdk()->getMerchantAccounts(),
        fn (MerchantAccountInterface $account) => $account->getCurrency() === 'GBP'
    );

    $merchantBeneficiary = $helper->merchantBeneficiary($account);

    $created = $helper->create(
        $helper->bankTransferMethod($merchantBeneficiary), $helper->user(), $account->getCurrency()
    );

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
})->depends('it creates a merchant payment');

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

\it('settles payment', function (PaymentCreatedInterface $created) {
    /** @var RedirectActionInterface $next */
    $next = $created->getDetails()->getAuthorizationFlowNextAction();

    \bankAction($next->getUri(), 'Execute');
    \sleep(10);

    /* @var PaymentSettledInterface $payment */
    $payment = $created->getDetails();

    \expect($payment)->toBeInstanceOf(PaymentSettledInterface::class);
    \expect($payment->getAuthorizationFlowConfig())->toBeInstanceOf(ConfigurationInterface::class);
    \expect($payment->getAuthorizationFlowConfig()->getRedirectReturnUri())->toBeString();
    \expect($payment->getSettledAt())->toBeInstanceOf(DateTimeInterface::class);

    \expect($payment->getPaymentSource())->toBeInstanceOf(PaymentSourceInterface::class);
    \expect($payment->getPaymentSource()->getId())->toBeString();
    \expect($payment->getPaymentSource()->getAccountHolderName())->toBeString();

    \var_dump($payment->getPaymentSource()->getAccountHolderName());

    return $created;
})->depends('it submits provider');
