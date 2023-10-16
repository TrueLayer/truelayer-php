<?php

declare(strict_types=1);

use TrueLayer\Constants\DateTime;
use TrueLayer\Interfaces\Payment\PaymentSettledInterface;
use TrueLayer\Interfaces\Payment\RefundAuthorizedInterface;
use TrueLayer\Interfaces\Payment\RefundExecutedInterface;
use TrueLayer\Interfaces\Payment\RefundFailedInterface;
use TrueLayer\Interfaces\Payment\RefundPendingInterface;
use TrueLayer\Interfaces\Payment\RefundRetrievedInterface;
use TrueLayer\Tests\Integration\Mocks\PaymentResponse;
use TrueLayer\Tests\Integration\Mocks\RefundResponse;

function assertRefundCommon(RefundRetrievedInterface $refund)
{
    \expect($refund->getId())->toBeString();
    \expect($refund->getStatus())->toBeString();
    \expect($refund->getReference())->toBe('TEST');
    \expect($refund->getCurrency())->toBe('GBP');
    \expect($refund->getAmountInMinor())->toBe(1);
    \expect($refund->getCreatedAt()->format(DateTime::FORMAT))->toBe('2022-02-04T13:40:23.798415Z');
}

\it('sends correct request on creation', function () {
    \client(RefundResponse::created())->refund()
        ->payment('1234')
        ->amountInMinor(100)
        ->reference('TEST')
        ->create();

    \expect(\getRequestPayload(1))->toMatchArray([
        'amount_in_minor' => 100,
        'reference' => 'TEST',
    ]);

    \expect(\getSentHttpRequests()[1]->getMethod())->toBe('POST');
    \expect(\getSentHttpRequests()[1]->getUri()->getPath())->toBe('/payments/1234/refunds');
});

\it('retrieves id on creation', function () {
    $refund = \client(RefundResponse::created())->refund()
        ->payment('1234')
        ->amountInMinor(100)
        ->reference('TEST')
        ->create();

    \expect($refund->getId())->toBe('56bbff85-9504-4cba-a63b-c781745ad3ed');
});

\it('sends correct request on retrieve refund', function () {
    \client(RefundResponse::pending())->getRefund('123', '456');

    \expect(\getSentHttpRequests()[1]->getMethod())->toBe('GET');
    \expect(\getSentHttpRequests()[1]->getUri()->getPath())->toBe('/payments/123/refunds/456');
});

\it('retrieves pending refund', function () {
    $refund = \client(RefundResponse::pending())->getRefund('123', '456');

    \assertRefundCommon($refund);
    \expect($refund)->toBeInstanceOf(RefundPendingInterface::class);
});

\it('retrieves authorised refund', function () {
    $refund = \client(RefundResponse::authorized())->getRefund('123', '456');

    \assertRefundCommon($refund);
    \expect($refund)->toBeInstanceOf(RefundAuthorizedInterface::class);
});

\it('retrieves executed refund', function () {
    /** @var RefundExecutedInterface $refund */
    $refund = \client(RefundResponse::executed())->getRefund('123', '456');

    \assertRefundCommon($refund);
    \expect($refund)->toBeInstanceOf(RefundExecutedInterface::class);
    \expect($refund->getExecutedAt()->format(DateTime::FORMAT))->toBe('2022-02-04T14:12:07.705938Z');
});

\it('retrieves failed refund', function () {
    /** @var RefundFailedInterface $refund */
    $refund = \client(RefundResponse::failed())->getRefund('123', '456');

    \assertRefundCommon($refund);
    \expect($refund)->toBeInstanceOf(RefundFailedInterface::class);
    \expect($refund->getFailedAt()->format(DateTime::FORMAT))->toBe('2022-02-06T22:26:48.849469Z');
    \expect($refund->getFailureReason())->toBeString();
});

\it('sends correct request on retrieve all refunds', function () {
    \client(RefundResponse::all())->getRefunds('123');

    \expect(\getSentHttpRequests()[1]->getMethod())->toBe('GET');
    \expect(\getSentHttpRequests()[1]->getUri()->getPath())->toBe('/payments/123/refunds');
});

\it('retrieves all refunds', function () {
    $refunds = \client(RefundResponse::all())->getRefunds('123');

    \expect($refunds[0])->toBeInstanceOf(RefundPendingInterface::class);
    \expect($refunds[1])->toBeInstanceOf(RefundAuthorizedInterface::class);
    \expect($refunds[2])->toBeInstanceOf(RefundExecutedInterface::class);
    \expect($refunds[3])->toBeInstanceOf(RefundFailedInterface::class);

    \assertRefundCommon($refunds[0]);
    \assertRefundCommon($refunds[1]);
    \assertRefundCommon($refunds[2]);
    \assertRefundCommon($refunds[3]);
});

\it('creates refund from payment', function () {
    $client = \client([PaymentResponse::settled(), RefundResponse::created()]);

    /** @var PaymentSettledInterface $payment */
    $payment = $client->getPayment('b9a7d0e9-4de9-425a-b282-cf2a4f998c5d');

    $refund = $payment->refund()->amountInMinor(1)->reference('TESTING')->create();

    \expect(\getRequestPayload(2))->toMatchArray([
        'amount_in_minor' => 1,
        'reference' => 'TESTING',
    ]);

    \expect(\getSentHttpRequests()[2]->getMethod())->toBe('POST');
    \expect(\getSentHttpRequests()[2]->getUri()->getPath())->toBe('/payments/b9a7d0e9-4de9-425a-b282-cf2a4f998c5d/refunds');
    \expect($refund->getId())->toBe('56bbff85-9504-4cba-a63b-c781745ad3ed');
});

it('sends custom idempotency key on refund creation', function () {
    $client = \client([PaymentResponse::settled(), RefundResponse::created()]);

    /** @var PaymentSettledInterface $payment */
    $payment = $client->getPayment('b9a7d0e9-4de9-425a-b282-cf2a4f998c5d');

    $requestOptions = $client->requestOptions()->idempotencyKey('refund-test-idempotency-key');
    $payment->refund()
        ->amountInMinor(1)
        ->reference('TESTING')
        ->requestOptions($requestOptions)
        ->create();

    \expect(getRequestIdempotencyKey(2))->toBe('refund-test-idempotency-key');
});

\it('retrieves refund from payment', function () {
    $client = \client([PaymentResponse::settled(), RefundResponse::pending()]);

    /** @var PaymentSettledInterface $payment */
    $payment = $client->getPayment('1');
    $refund = $payment->getRefund('2');

    \assertRefundCommon($refund);
});

\it('retrieves all refunds from payment', function () {
    $client = \client([PaymentResponse::settled(), RefundResponse::all()]);

    /** @var PaymentSettledInterface $payment */
    $payment = $client->getPayment('1');
    $refunds = $payment->getRefunds();

    \assertRefundCommon($refunds[0]);
    \assertRefundCommon($refunds[1]);
    \assertRefundCommon($refunds[2]);
    \assertRefundCommon($refunds[3]);
});
