<?php

declare(strict_types=1);

use Illuminate\Support\Arr;
use TrueLayer\Interfaces\MerchantAccount\MerchantAccountInterface;
use TrueLayer\Interfaces\Payment\PaymentCreatedInterface;
use TrueLayer\Interfaces\Payment\PaymentSettledInterface;
use TrueLayer\Interfaces\Payment\RefundRetrievedInterface;

function assertRefundCommon(RefundRetrievedInterface $refund)
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
        \sleep(10);

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
    \assertRefundCommon($refunds[0]);

    $refund = \client()->getRefund($paymentCreated, $refunds[0]->getId());
    \assertRefundCommon($refund);
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

\it('retrieves refunds from payment', function (PaymentCreatedInterface $paymentCreated) {
    /** @var PaymentSettledInterface $payment */
    $payment = $paymentCreated->getDetails();
    $refunds = $payment->getRefunds();

    \expect(\count($refunds))->toBeGreaterThan(0);
    \assertRefundCommon($refunds[0]);

    $refund = \client()->getRefund($paymentCreated, $refunds[0]->getId());
    \assertRefundCommon($refund);
})->depends('it creates a refund');
