<?php

declare(strict_types=1);

use Illuminate\Support\Arr;
use Ramsey\Uuid\Uuid;
use TrueLayer\Interfaces\MerchantAccount\MerchantAccountInterface;
use TrueLayer\Interfaces\Payment\PaymentCreatedInterface;
use TrueLayer\Interfaces\Payment\PaymentSettledInterface;
use TrueLayer\Interfaces\Payment\RefundRetrievedInterface;

function assertRefundCommonAcceptance(RefundRetrievedInterface $refund)
{
    \expect($refund)->toBeInstanceOf(RefundRetrievedInterface::class);
    \expect($refund->getId())->toBeString();
    \expect($refund->getStatus())->toBeString();
    \expect($refund->getReference())->toBe('refund');
    \expect($refund->getCurrency())->toBe('GBP');
    \expect($refund->getAmountInMinor())->toBe(1);
    \expect($refund->getCreatedAt())->toBeInstanceOf(DateTimeInterface::class);
}

\it('creates a refund', function () {
    try {
        $helper = \paymentHelper();
        $client = $helper->client();

        $account = Arr::first(
            $helper->client()->getMerchantAccounts(),
            fn (MerchantAccountInterface $account) => $account->getCurrency() === 'GBP'
        );

        $merchantBeneficiary = $helper->merchantBeneficiary($account);

        $created = $helper->create(
            $helper->bankTransferMethod($merchantBeneficiary), $helper->user(), $account->getCurrency()
        );

        $client->startPaymentAuthorization($created, 'https://console.truelayer.com/redirect-page');
        $client->submitPaymentProvider($created, 'mock-payments-gb-redirect');

        /** @var RedirectActionInterface $next */
        $next = $created->getDetails()->getAuthorizationFlowNextAction();

        \bankAction($next->getUri(), 'Execute');
        \sleep(120);

        $response = $client->refund()
            ->payment($created)
            ->amountInMinor(1)
            ->reference('refund')
            ->create();

        \expect($response->getId())->toBeString();

        return $created;
    } catch (\TrueLayer\Exceptions\ApiResponseUnsuccessfulException $e) {
        throw $e;
    }
});

\it('retrieves refunds', function (PaymentCreatedInterface $paymentCreated) {
    /** @var RefundRetrievedInterface[] $refunds */
    $refunds = \client()->getRefunds($paymentCreated);

    \expect(\count($refunds))->toBeGreaterThan(0);
    \assertRefundCommonAcceptance($refunds[0]);

    $refund = \client()->getRefund($paymentCreated, $refunds[0]->getId());
    \assertRefundCommonAcceptance($refund);
})->depends('it creates a refund');

\it('creates a refund from a payment', function (PaymentCreatedInterface $paymentCreated) {
    /** @var PaymentSettledInterface $payment */
    $payment = $paymentCreated->getDetails();

    $refundId = $payment->refund()
        ->amountInMinor(1)
        ->reference('refund')
        ->create()
        ->getId();

    \expect($refundId)->toBeString();
})->depends('it creates a refund');

\it('creates refunds with a custom idempotency key', function (PaymentCreatedInterface $paymentCreated) {
    /** @var PaymentSettledInterface $payment */
    $payment = $paymentCreated->getDetails();

    $requestOptions = \paymentHelper()->client()->requestOptions()->idempotencyKey(
        Uuid::uuid1()->toString()
    );

    $refund1 = $payment->refund()
        ->amountInMinor(1)
        ->reference('refund')
        ->requestOptions($requestOptions)
        ->create()
        ->getId();

    $refund2 = $payment->refund()
        ->amountInMinor(1)
        ->reference('refund')
        ->requestOptions($requestOptions)
        ->create()
        ->getId();

    $refund3 = $payment->refund()
        ->amountInMinor(1)
        ->reference('refund')
        ->requestOptions(
            \paymentHelper()->client()->requestOptions()->idempotencyKey(Uuid::uuid1()->toString())
        )
        ->create()
        ->getId();

    \expect($refund1)->toBe($refund2);
    \expect($refund1)->not->toBe($refund3);
    \expect($refund2)->not->toBe($refund3);
})->depends('it creates a refund');

\it('retrieves refunds from payment', function (PaymentCreatedInterface $paymentCreated) {
    /** @var PaymentSettledInterface $payment */
    $payment = $paymentCreated->getDetails();
    $refunds = $payment->getRefunds();

    \expect(\count($refunds))->toBeGreaterThan(0);
    \assertRefundCommonAcceptance($refunds[0]);

    $refund = \client()->getRefund($paymentCreated, $refunds[0]->getId());
    \assertRefundCommonAcceptance($refund);
})->depends('it creates a refund');
