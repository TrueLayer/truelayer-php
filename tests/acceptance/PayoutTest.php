<?php

declare(strict_types=1);

use Ramsey\Uuid\Uuid;
use TrueLayer\Constants\Currencies;
use TrueLayer\Interfaces\MerchantAccount\MerchantAccountInterface;
use TrueLayer\Interfaces\Payment\AuthorizationFlow\Action\RedirectActionInterface;
use TrueLayer\Interfaces\Payment\PaymentSettledInterface;
use TrueLayer\Interfaces\Payout\Beneficiary\BusinessAccountBeneficiaryInterface;
use TrueLayer\Interfaces\Payout\Beneficiary\ExternalAccountBeneficiaryInterface;
use TrueLayer\Interfaces\Payout\Beneficiary\PaymentSourceBeneficiaryInterface;
use TrueLayer\Interfaces\Payout\PayoutRetrievedInterface;
use TrueLayer\Services\Util\Arr;

\it('creates a closed loop payout', function () {
    $helper = \paymentHelper();

    $account = Arr::first(
        $helper->client()->getMerchantAccounts(),
        fn (MerchantAccountInterface $account) => $account->getCurrency() === 'GBP'
    );

    $merchantBeneficiary = $helper->merchantBeneficiary($account);

    $created = $helper->create(
        $helper->bankTransferMethod($merchantBeneficiary), $helper->user(), $account->getCurrency()
    );

    \client()->startPaymentAuthorization($created, 'https://console.truelayer.com/redirect-page');
    \client()->submitPaymentProvider($created, 'mock-payments-gb-redirect');

    /** @var RedirectActionInterface $next */
    $next = $created->getDetails()->getAuthorizationFlowNextAction();
    \bankAction($next->getUri(), 'Execute');
    \sleep(120);

    /* @var PaymentSettledInterface $payment */
    $payment = $created->getDetails();

    $client = \client();

    $payoutBeneficiary = $client->payoutBeneficiary()->paymentSource()
        ->paymentSourceId($payment->getPaymentSource()->getId())
        ->reference('Test reference')
        ->userId($payment->getUserId());

    $response = $client->payout()
        ->amountInMinor(1)
        ->currency(Currencies::GBP)
        ->merchantAccountId($account->getId())
        ->beneficiary($payoutBeneficiary)
        ->create();

    \expect($response->getId())->toBeString();

    /** @var PayoutRetrievedInterface $payout */
    $payout = $client->getPayout($response->getId());

    \expect($payout)->toBeInstanceOf(PayoutRetrievedInterface::class);
    \expect($payout->getCurrency())->toBe(Currencies::GBP);
    \expect($payout->getAmountInMinor())->toBe(1);
    \expect($payout->getCreatedAt())->toBeInstanceOf(DateTimeInterface::class);

    /** @var PaymentSourceBeneficiaryInterface $beneficiary */
    $beneficiary = $payout->getBeneficiary();

    \expect($beneficiary)->toBeInstanceOf(PaymentSourceBeneficiaryInterface::class);
    \expect($beneficiary->getPaymentSourceId())->toBe($payment->getPaymentSource()->getId());
    \expect($beneficiary->getReference())->toBe('Test reference');
    \expect($beneficiary->getUserId())->toBe($payment->getUserId());
});

\it('creates an open loop payout', function () {
    $client = \client();

    $account = Arr::first(
        $client->getMerchantAccounts(),
        fn (MerchantAccountInterface $account) => $account->getCurrency() === 'GBP'
    );

    $payoutBeneficiary = $client->payoutBeneficiary()->externalAccount()
        ->accountIdentifier(
            $client->accountIdentifier()->iban()->iban('GB29NWBK60161331926819')
        )
        ->accountHolderName('Test name')
        ->reference('Test reference');

    $response = $client->payout()
        ->amountInMinor(1)
        ->currency(Currencies::GBP)
        ->merchantAccountId($account->getId())
        ->beneficiary($payoutBeneficiary)
        ->create();

    \expect($response->getId())->toBeString();

    /** @var PayoutRetrievedInterface $payout */
    $payout = $client->getPayout($response->getId());

    \expect($payout)->toBeInstanceOf(PayoutRetrievedInterface::class);
    \expect($payout->getCurrency())->toBe(Currencies::GBP);
    \expect($payout->getAmountInMinor())->toBe(1);
    \expect($payout->getCreatedAt())->toBeInstanceOf(DateTimeInterface::class);

    /** @var ExternalAccountBeneficiaryInterface $beneficiary */
    $beneficiary = $payout->getBeneficiary();

    \expect($beneficiary)->toBeInstanceOf(ExternalAccountBeneficiaryInterface::class);
    \expect($beneficiary->getAccountHolderName())->toBe('Test name');
    \expect($beneficiary->getReference())->toBe('Test reference');
});

\it('creates a payout to a business account', function () {
    $helper = \paymentHelper();

    $account = Arr::first(
        $helper->client()->getMerchantAccounts(),
        fn (MerchantAccountInterface $account) => $account->getCurrency() === 'GBP'
    );

    $merchantBeneficiary = $helper->merchantBeneficiary($account);

    $created = $helper->create(
        $helper->bankTransferMethod($merchantBeneficiary), $helper->user(), $account->getCurrency()
    );

    \client()->startPaymentAuthorization($created, 'https://console.truelayer.com/redirect-page');
    \client()->submitPaymentProvider($created, 'mock-payments-gb-redirect');

    /** @var RedirectActionInterface $next */
    $next = $created->getDetails()->getAuthorizationFlowNextAction();
    \bankAction($next->getUri(), 'Execute');
    \sleep(120);

    /* @var PaymentSettledInterface $payment */
    $payment = $created->getDetails();

    $client = \client();

    $payoutBeneficiary = $client->payoutBeneficiary()->businessAccount()
        ->reference('Test reference');

    $response = $client->payout()
        ->amountInMinor(1)
        ->currency(Currencies::GBP)
        ->merchantAccountId($account->getId())
        ->beneficiary($payoutBeneficiary)
        ->create();

    \expect($response->getId())->toBeString();

    /** @var PayoutRetrievedInterface $payout */
    $payout = $client->getPayout($response->getId());

    \expect($payout)->toBeInstanceOf(PayoutRetrievedInterface::class);
    \expect($payout->getCurrency())->toBe(Currencies::GBP);
    \expect($payout->getAmountInMinor())->toBe(1);
    \expect($payout->getCreatedAt())->toBeInstanceOf(DateTimeInterface::class);

    /** @var BusinessAccountBeneficiaryInterface $beneficiary */
    $beneficiary = $payout->getBeneficiary();

    \expect($beneficiary)->toBeInstanceOf(BusinessAccountBeneficiaryInterface::class);
    \expect($beneficiary->getReference())->toBe('Test reference');
});

\it('creates a payout with custom idempotency key', function () {
    $client = \client();

    $requestOptions = \paymentHelper()->client()->requestOptions()->idempotencyKey(
        Uuid::uuid1()->toString()
    );

    $account = Arr::first(
        $client->getMerchantAccounts(),
        fn (MerchantAccountInterface $account) => $account->getCurrency() === 'GBP'
    );

    $payoutBeneficiary = $client->payoutBeneficiary()->externalAccount()
        ->accountIdentifier(
            $client->accountIdentifier()->iban()->iban('GB29NWBK60161331926819')
        )
        ->accountHolderName('Test name')
        ->reference('Test reference');

    $payout1 = $client->payout()
        ->amountInMinor(1)
        ->currency(Currencies::GBP)
        ->merchantAccountId($account->getId())
        ->beneficiary($payoutBeneficiary)
        ->requestOptions($requestOptions)
        ->create()
        ->getId();

    $payout2 = $client->payout()
        ->amountInMinor(1)
        ->currency(Currencies::GBP)
        ->merchantAccountId($account->getId())
        ->beneficiary($payoutBeneficiary)
        ->requestOptions($requestOptions)
        ->create()
        ->getId();

    $payout3 = $client->payout()
        ->amountInMinor(1)
        ->currency(Currencies::GBP)
        ->merchantAccountId($account->getId())
        ->beneficiary($payoutBeneficiary)
        ->requestOptions($client->requestOptions()->idempotencyKey(Uuid::uuid1()->toString()))
        ->create()
        ->getId();

    \expect($payout1)->toBe($payout2);
    \expect($payout1)->not->toBe($payout3);
    \expect($payout2)->not->toBe($payout3);
});

\it('creates a payout with the right metadata', function ($metadata) {
    $helper = \paymentHelper();

    $account = Arr::first(
        $helper->client()->getMerchantAccounts(),
        fn (MerchantAccountInterface $account) => $account->getCurrency() === 'GBP'
    );

    $merchantBeneficiary = $helper->merchantBeneficiary($account);

    //    $created = $helper->create(
    //        $helper->bankTransferMethod($merchantBeneficiary), $helper->user(), $account->getCurrency()
    //    );
    //
    //    \client()->startPaymentAuthorization($created, 'https://console.truelayer.com/redirect-page');
    //    \client()->submitPaymentProvider($created, 'mock-payments-gb-redirect');
    //
    //    /** @var RedirectActionInterface $next */
    //    $next = $created->getDetails()->getAuthorizationFlowNextAction();
    //    \bankAction($next->getUri(), 'Execute');
    //    \sleep(15);

    //    /* @var PaymentSettledInterface $payment */
    //    $payment = $created->getDetails();

    $client = \client();

    $payoutBeneficiary = $client->payoutBeneficiary()->externalAccount()
        ->accountIdentifier(
            $client->accountIdentifier()->iban()->iban('GB29NWBK60161331926819')
        )
        ->accountHolderName('Test name')
        ->reference('Test reference');

    $response = $client->payout()
        ->amountInMinor(1)
        ->currency(Currencies::GBP)
        ->merchantAccountId($account->getId())
        ->beneficiary($payoutBeneficiary)
        ->metadata($metadata)
        ->create();

    /** @var PayoutRetrievedInterface $payout */
    $payout = $client->getPayout($response->getId());

    \expect($payout)->toBeInstanceOf(PayoutRetrievedInterface::class);
    \expect($payout->getMetadata())->toBe($metadata);
})->with([
    'some metadata' => [
        ['foo' => 'bar'],
    ],
    'no metadata' => [[]],
]);

\it('creates payout with beneficiary address', function () {
    $client = \client();

    $account = Arr::first(
        $client->getMerchantAccounts(),
        fn (MerchantAccountInterface $account) => $account->getCurrency() === 'GBP'
    );

    $payoutBeneficiary = $client->payoutBeneficiary()->externalAccount()
        ->accountIdentifier(
            $client->accountIdentifier()->iban()->iban('GB29NWBK60161331926819')
        )
        ->accountHolderName('Test name')
        ->reference('Test reference');

    $payoutBeneficiary->address(null)
        ->addressLine1('The Gilbert')
        ->addressLine2('City of')
        ->city('London')
        ->state('Greater London')
        ->zip('EC2A 1PX')
        ->countryCode('GB');

    $response = $client->payout()
        ->amountInMinor(1)
        ->currency(Currencies::GBP)
        ->merchantAccountId($account->getId())
        ->beneficiary($payoutBeneficiary)
        ->create();

    \expect($response->getId())->toBeString();

    /** @var PayoutRetrievedInterface $payout */
    $payout = $client->getPayout($response->getId());

    \expect($payout)->toBeInstanceOf(PayoutRetrievedInterface::class);

    /** @var ExternalAccountBeneficiaryInterface $beneficiary */
    $beneficiary = $payout->getBeneficiary();

    \expect($beneficiary)->toBeInstanceOf(ExternalAccountBeneficiaryInterface::class);
});

\it('creates payout with beneficiary date of birth', function () {
    $client = \client();

    $account = Arr::first(
        $client->getMerchantAccounts(),
        fn (MerchantAccountInterface $account) => $account->getCurrency() === 'GBP'
    );

    $payoutBeneficiary = $client->payoutBeneficiary()->externalAccount()
        ->accountIdentifier(
            $client->accountIdentifier()->iban()->iban('GB29NWBK60161331926819')
        )
        ->accountHolderName('Test name')
        ->reference('Test reference')
        ->dateOfBirth('1990-01-31');

    $response = $client->payout()
        ->amountInMinor(1)
        ->currency(Currencies::GBP)
        ->merchantAccountId($account->getId())
        ->beneficiary($payoutBeneficiary)
        ->create();

    \expect($response->getId())->toBeString();

    /** @var PayoutRetrievedInterface $payout */
    $payout = $client->getPayout($response->getId());

    \expect($payout)->toBeInstanceOf(PayoutRetrievedInterface::class);

    /** @var ExternalAccountBeneficiaryInterface $beneficiary */
    $beneficiary = $payout->getBeneficiary();

    \expect($beneficiary)->toBeInstanceOf(ExternalAccountBeneficiaryInterface::class);
});

\it('creates payout with scheme selection', function () {
    $client = \client();

    $account = Arr::first(
        $client->getMerchantAccounts(),
        fn (MerchantAccountInterface $account) => $account->getCurrency() === 'GBP'
    );

    $payoutBeneficiary = $client->payoutBeneficiary()->externalAccount()
        ->accountIdentifier(
            $client->accountIdentifier()->iban()->iban('GB29NWBK60161331926819')
        )
        ->accountHolderName('Test name')
        ->reference('Test reference');

    $schemeSelection = $client->payoutSchemeSelection()->instantOnly();

    $response = $client->payout()
        ->amountInMinor(1)
        ->currency(Currencies::GBP)
        ->merchantAccountId($account->getId())
        ->beneficiary($payoutBeneficiary)
        ->schemeSelection($schemeSelection)
        ->create();

    \expect($response->getId())->toBeString();

    /** @var PayoutRetrievedInterface $payout */
    $payout = $client->getPayout($response->getId());

    \expect($payout)->toBeInstanceOf(PayoutRetrievedInterface::class);
});
