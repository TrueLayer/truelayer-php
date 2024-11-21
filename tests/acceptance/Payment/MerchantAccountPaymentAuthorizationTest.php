<?php

declare(strict_types=1);

use Ramsey\Uuid\Uuid;
use TrueLayer\Constants\AuthorizationFlowActionTypes;
use TrueLayer\Constants\AuthorizationFlowStatusTypes;
use TrueLayer\Constants\Endpoints;
use TrueLayer\Constants\UserPoliticalExposures;
use TrueLayer\Interfaces\MerchantAccount\MerchantAccountInterface;
use TrueLayer\Interfaces\Payment\AuthorizationFlow\Action\ProviderSelectionActionInterface;
use TrueLayer\Interfaces\Payment\AuthorizationFlow\Action\RedirectActionInterface;
use TrueLayer\Interfaces\Payment\AuthorizationFlow\ConfigurationInterface;
use TrueLayer\Interfaces\Payment\Beneficiary\MerchantBeneficiaryInterface;
use TrueLayer\Interfaces\Payment\PaymentAttemptFailedInterface;
use TrueLayer\Interfaces\Payment\PaymentAuthorizingInterface;
use TrueLayer\Interfaces\Payment\PaymentCreatedInterface;
use TrueLayer\Interfaces\Payment\PaymentFailedInterface;
use TrueLayer\Interfaces\Payment\PaymentRetrievedInterface;
use TrueLayer\Interfaces\Payment\PaymentSettledInterface;
use TrueLayer\Interfaces\Payment\PaymentSourceInterface;
use TrueLayer\Interfaces\PaymentMethod\BankTransferPaymentMethodInterface;
use TrueLayer\Interfaces\Provider\ProviderInterface;
use TrueLayer\Interfaces\Remitter\RemitterVerification\AutomatedRemitterVerificationInterface;
use TrueLayer\Services\Util\Arr;

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

    /** @var BankTransferPaymentMethodInterface $paymentMethod */
    $paymentMethod = $created->getDetails()->getPaymentMethod();
    \expect($paymentMethod)->toBeInstanceOf(BankTransferPaymentMethodInterface::class);
    \expect($paymentMethod->getBeneficiary()->getReference())->toBe('TEST');
    \expect($paymentMethod->isPaymentRetryEnabled())->toBe(false);

    return $created;
});

\it('cancels a merchant payment', function () {
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

    $payment = $created->getDetails();

    /** @var PaymentFailedInterface $cancelled */
    $cancelled = $payment->cancel();

    \expect($cancelled)->toBeInstanceOf(PaymentFailedInterface::class);
    \expect($cancelled->getFailureReason())->toBe('canceled');
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

\it('creates payment with user political exposure', function () {
    $helper = \paymentHelper();

    $payment = $helper->client()->payment()
        ->paymentMethod($helper->bankTransferMethod($helper->sortCodeBeneficiary()))
        ->amountInMinor(10)
        ->currency('GBP')
        ->user($helper->user()->politicalExposure(UserPoliticalExposures::CURRENT))
        ->create();

    \expect($payment)->toBeInstanceOf(PaymentCreatedInterface::class);
    \expect($payment->getId())->toBeString();
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

\it('creates payment without excluded provider ids', function () {
    $helper = \paymentHelper();

    $account = Arr::first(
        $helper->client()->getMerchantAccounts(),
        fn (MerchantAccountInterface $account) => $account->getCurrency() === 'GBP'
    );

    $filter = $helper->client()->providerFilter()
        ->countries([TrueLayer\Constants\Countries::ES])
        ->releaseChannel(TrueLayer\Constants\ReleaseChannels::GENERAL_AVAILABILITY);

    $providerSelection = $helper->client()->providerSelection()
        ->userSelected()
        ->filter($filter);

    $paymentMethod = $helper->client()->paymentMethod()->bankTransfer()
        ->beneficiary($helper->merchantBeneficiary($account))
        ->providerSelection($providerSelection);

    $created = $helper->create(
        $paymentMethod, $helper->user(), $account->getCurrency()
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

\it('handles a merchant payment with retry enabled', function () {
    $helper = \paymentHelper();
    $client = $helper->client();

    $account = Arr::first(
        $helper->client()->getMerchantAccounts(),
        fn (MerchantAccountInterface $account) => $account->getCurrency() === 'GBP'
    );

    $merchantBeneficiary = $helper->merchantBeneficiary($account);
    $paymentMethod = $helper->bankTransferMethod($merchantBeneficiary)->enablePaymentRetry();
    $created = $helper->create($paymentMethod, $helper->user(), $account->getCurrency());

    \expect($created)->toBeInstanceOf(PaymentCreatedInterface::class);

    /** @var BankTransferPaymentMethodInterface $paymentMethod */
    $paymentMethod = $created->getDetails()->getPaymentMethod();
    \expect($paymentMethod->isPaymentRetryEnabled())->toBe(true);

    $payload = $client->paymentAuthorizationFlow($created)
        ->returnUri('https://console.truelayer.com/redirect-page')
        ->enableProviderSelection()
        ->toArray();
    $payload['retry'] = new stdClass();

    $client->getApiClient()->request()
        ->payload($payload)
        ->uri(\str_replace('{id}', $created->getId(), Endpoints::PAYMENTS_START_AUTH_FLOW))
        ->post();

    $client->submitPaymentProvider($created, 'mock-payments-gb-redirect');

    /** @var RedirectActionInterface $next */
    $next = $created->getDetails()->getAuthorizationFlowNextAction();
    \bankAction($next->getUri(), 'RejectExecution');
    \sleep(20);

    /** @var PaymentAttemptFailedInterface $retrievedPayment */
    $retrievedPayment = $created->getDetails();
    \expect($retrievedPayment)->toBeInstanceOf(PaymentAttemptFailedInterface::class);
    \expect($retrievedPayment->getPaymentMethod()->isPaymentRetryEnabled())->toBe(true);
});

\it('creates a merchant payment with automated remitter verification', function () {
    $helper = \paymentHelper();

    $account = Arr::first(
        $helper->client()->getMerchantAccounts(),
        fn (MerchantAccountInterface $account) => $account->getCurrency() === 'GBP'
    );

    $merchantBeneficiary = $helper->merchantBeneficiary($account)
        ->verification($helper->remitterVerification()->automated()->remitterName(true));

    $created = $helper->create(
        $helper->bankTransferMethod($merchantBeneficiary), $helper->user(), $account->getCurrency()
    );

    \expect($created)->toBeInstanceOf(PaymentCreatedInterface::class);

    /**
     * @var BankTransferPaymentMethodInterface $receivedPaymentMethod
     */
    $receivedPaymentMethod = $created->getDetails()->getPaymentMethod();
    \expect($receivedPaymentMethod)->toBeInstanceOf(BankTransferPaymentMethodInterface::class);

    /**
     * @var MerchantBeneficiaryInterface $receivedBeneficiary
     */
    $receivedBeneficiary = $receivedPaymentMethod->getBeneficiary();
    \expect($receivedBeneficiary)->toBeInstanceOf(MerchantBeneficiaryInterface::class);

    /**
     * @var AutomatedRemitterVerificationInterface $receivedRemitterVerification
     */
    $receivedRemitterVerification = $receivedBeneficiary->getVerification();
    \expect($receivedRemitterVerification)->toBeInstanceOf(AutomatedRemitterVerificationInterface::class);
    \expect($receivedRemitterVerification->getRemitterName())->toBeTrue();
    \expect($receivedRemitterVerification->getRemitterDateOfBirth())->toBeFalse();
});

\it('creates payment with statement reference', function () {
    $helper = \paymentHelper();

    $account = Arr::first(
        $helper->client()->getMerchantAccounts(),
        fn (MerchantAccountInterface $account) => $account->getCurrency() === 'GBP'
    );

    $merchantBeneficiary = $helper->merchantBeneficiary($account);
    $merchantBeneficiary->statementReference('TEST');

    $payment = $helper->create(
        $helper->bankTransferMethod($merchantBeneficiary), $helper->user(), $account->getCurrency()
    );

    $fetched = $payment->getDetails();

    \expect($payment)->toBeInstanceOf(PaymentCreatedInterface::class);
    \expect($payment->getId())->toBeString();
    \expect($fetched)->toBeInstanceOf(PaymentRetrievedInterface::class);
    \expect($fetched->getId())->toBeString();
    \expect($fetched->getPaymentMethod()->getBeneficiary()->getStatementReference())->toBe('TEST');
})->only();
