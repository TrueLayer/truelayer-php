<?php

declare(strict_types=1);

use Illuminate\Support\Arr;
use Ramsey\Uuid\Uuid;
use TrueLayer\Constants\AuthorizationFlowActionTypes;
use TrueLayer\Constants\AuthorizationFlowStatusTypes;
use TrueLayer\Interfaces\MerchantAccount\MerchantAccountInterface;
use TrueLayer\Interfaces\Payment\AuthorizationFlow\Action\ProviderSelectionActionInterface;
use TrueLayer\Interfaces\Payment\AuthorizationFlow\Action\RedirectActionInterface;
use TrueLayer\Interfaces\Payment\AuthorizationFlow\ConfigurationInterface;
use TrueLayer\Interfaces\Payment\PaymentAuthorizingInterface;
use TrueLayer\Interfaces\Payment\PaymentCreatedInterface;
use TrueLayer\Interfaces\Payment\PaymentRetrievedInterface;
use TrueLayer\Interfaces\Payment\PaymentSettledInterface;
use TrueLayer\Interfaces\Payment\PaymentSourceInterface;
use TrueLayer\Interfaces\PaymentMethod\BankTransferPaymentMethodInterface;
use TrueLayer\Interfaces\Provider\ProviderInterface;

\it('creates a merchant payment', function () {
    $helper = \paymentHelper();

    $account = Arr::first(
        $helper->client()->getMerchantAccounts(),
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

    $paymentMethod = $created->getDetails()->getPaymentMethod();
    \expect($paymentMethod)->toBeInstanceOf(BankTransferPaymentMethodInterface::class);
    \expect($paymentMethod->getBeneficiary()->getReference())->toBe('TEST');

    return $created;
});

\it('starts payment authorization', function (PaymentCreatedInterface $created) {
    $response = \client()->startPaymentAuthorization($created, 'https://console.truelayer.com/redirect-page');

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

    return $created;
})->depends('it submits provider');

\it('settles payment', function (PaymentCreatedInterface $created) {
    /** @var RedirectActionInterface $next */
    $next = $created->getDetails()->getAuthorizationFlowNextAction();

    \bankAction($next->getUri(), 'Execute');
    \sleep(120);

    /* @var PaymentSettledInterface $payment */
    $payment = $created->getDetails();

    \expect($payment)->toBeInstanceOf(PaymentSettledInterface::class);
    \expect($payment->getAuthorizationFlowConfig())->toBeInstanceOf(ConfigurationInterface::class);
    \expect($payment->getAuthorizationFlowConfig()->getRedirectReturnUri())->toBeString();
    \expect($payment->getSettledAt())->toBeInstanceOf(DateTimeInterface::class);

    \expect($payment->getPaymentSource())->toBeInstanceOf(PaymentSourceInterface::class);
    \expect($payment->getPaymentSource()->getId())->toBeString();
    \expect($payment->getPaymentSource()->getAccountHolderName())->toBeString();

    return $created;
})->depends('it submits provider');

\it('creates payment without metadata', function () {
    $helper = \paymentHelper();

    $payment = $helper->client()->payment()
        ->paymentMethod($helper->bankTransferMethod($helper->sortCodeBeneficiary()))
        ->amountInMinor(10)
        ->currency('GBP')
        ->user($helper->user())
        ->create();

    $fetched = $payment->getDetails();

    \expect($payment)->toBeInstanceOf(PaymentCreatedInterface::class);
    \expect($payment->getId())->toBeString();
    \expect($fetched)->toBeInstanceOf(PaymentRetrievedInterface::class);
    \expect($fetched->getId())->toBeString();
});

\it('creates payment with user address', function () {
    $helper = \paymentHelper();

    $payment = $helper->client()->payment()
        ->paymentMethod($helper->bankTransferMethod($helper->sortCodeBeneficiary()))
        ->amountInMinor(10)
        ->currency('GBP')
        ->user($helper->userWithAddress())
        ->create();

    $fetched = $payment->getDetails();

    \expect($payment)->toBeInstanceOf(PaymentCreatedInterface::class);
    \expect($payment->getId())->toBeString();
    \expect($fetched)->toBeInstanceOf(PaymentRetrievedInterface::class);
    \expect($fetched->getId())->toBeString();
});

\it('creates payment with valid user date of birth', function () {
    $helper = \paymentHelper();

    $payment = $helper->client()->payment()
        ->paymentMethod($helper->bankTransferMethod($helper->sortCodeBeneficiary()))
        ->amountInMinor(10)
        ->currency('GBP')
        ->user($helper->userWithDateOfBirth('2024-01-01'))
        ->create();

    $fetched = $payment->getDetails();

    \expect($payment)->toBeInstanceOf(PaymentCreatedInterface::class);
    \expect($payment->getId())->toBeString();
    \expect($fetched)->toBeInstanceOf(PaymentRetrievedInterface::class);
    \expect($fetched->getId())->toBeString();
});

\it('throws exception when creating payment with invalid user date of birth', function () {
    $helper = \paymentHelper();

    $helper->client()->payment()
        ->paymentMethod($helper->bankTransferMethod($helper->sortCodeBeneficiary()))
        ->amountInMinor(10)
        ->currency('GBP')
        ->user($helper->userWithDateOfBirth('invalid date'))
        ->create();
})->throws(TrueLayer\Exceptions\ValidationException::class);

\it('creates payment with idempotency key', function () {
    $helper = \paymentHelper();

    $requestOptions = \paymentHelper()->client()->requestOptions()->idempotencyKey(
        Uuid::uuid1()->toString()
    );

    $payment1 = $helper->client()->payment()
        ->paymentMethod($helper->bankTransferMethod($helper->sortCodeBeneficiary()))
        ->amountInMinor(10)
        ->currency('GBP')
        ->user($helper->user())
        ->requestOptions($requestOptions)
        ->create()
        ->getId();

    $payment2 = $helper->client()->payment()
        ->paymentMethod($helper->bankTransferMethod($helper->sortCodeBeneficiary()))
        ->amountInMinor(10)
        ->currency('GBP')
        ->user($helper->user())
        ->requestOptions($requestOptions)
        ->create()
        ->getId();

    $payment3 = $helper->client()->payment()
        ->paymentMethod($helper->bankTransferMethod($helper->sortCodeBeneficiary()))
        ->amountInMinor(10)
        ->currency('GBP')
        ->user($helper->user())
        ->requestOptions($helper->client()->requestOptions()->idempotencyKey(Uuid::uuid1()->toString()))
        ->create()
        ->getId();

    \expect($payment1)->toBe($payment2);
    \expect($payment1)->not->toBe($payment3);
    \expect($payment2)->not->toBe($payment3);
});
