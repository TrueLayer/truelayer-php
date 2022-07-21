<?php

declare(strict_types=1);

use TrueLayer\Constants\DateTime;
use TrueLayer\Entities\Payment\PaymentRetrieved\PaymentSource;
use TrueLayer\Interfaces\AccountIdentifier\IbanDetailsInterface;
use TrueLayer\Interfaces\AccountIdentifier\ScanDetailsInterface;
use TrueLayer\Interfaces\Webhook\PaymentEventInterface;
use TrueLayer\Interfaces\Webhook\PaymentExecutedEventInterface;
use TrueLayer\Interfaces\Webhook\PaymentFailedEventInterface;
use TrueLayer\Interfaces\Webhook\PaymentMethod\BankTransferPaymentMethodInterface;
use TrueLayer\Interfaces\Webhook\PaymentMethod\MandatePaymentMethodInterface;
use TrueLayer\Interfaces\Webhook\PaymentMethod\PaymentMethodInterface;
use TrueLayer\Interfaces\Webhook\PaymentSettledEventInterface;
use TrueLayer\Tests\Integration\Mocks\WebhookPayload;

\it('handles common payment data', function (string $body) {
    /** @var PaymentEventInterface $event */
    $event = null;

    \webhook($body)->handler(function (PaymentEventInterface $evt) use (&$event) {
        $event = $evt;
    })->execute();

    \expect($event)->toBeInstanceOf(PaymentEventInterface::class);
    \expect($event->getEventId())->toBe('b8d4dda0-ff2c-4d77-a6da-4615e4bad941');
    \expect($event->getEventVersion())->toBe(1);
    \expect($event->getPaymentId())->toBe('60c0a60ed8d7-4e5b-ac79-401b1d8a8633');
    \expect($event->getTimestamp()->format(DateTime::FORMAT))->toBe('2022-02-16T16:21:14.000000Z');
    \expect($event->getBody())->toHaveKey('event_id');
    \expect($event->getPaymentMethod())->toBeInstanceOf(PaymentMethodInterface::class);
})->with([
    'executed' => WebhookPayload::paymentExecuted(),
    'settled' => WebhookPayload::paymentSettled(),
    'failed' => WebhookPayload::paymentFailed(),
]);

\it('handles payment executed', function () {
    /** @var PaymentExecutedEventInterface $event */
    $event = null;

    // bank transfer
    \webhook(WebhookPayload::paymentExecuted())->handler(function (PaymentExecutedEventInterface $evt) use (&$event) {
        $event = $evt;
    })->execute();

    \expect($event)->toBeInstanceOf(PaymentExecutedEventInterface::class);
    \expect($event->getExecutedAt()->format(DateTime::FORMAT))->toBe('2021-12-25T15:00:00.000000Z');
    \expect($event->getType())->toBe('payment_executed');
});

\it('handles payment settled', function () {
    /** @var PaymentSettledEventInterface $event */
    $event = null;

    // bank transfer
    \webhook(WebhookPayload::paymentSettled())->handler(function (PaymentSettledEventInterface $evt) use (&$event) {
        $event = $evt;
    })->execute();

    \expect($event)->toBeInstanceOf(PaymentSettledEventInterface::class);
    \expect($event->getSettledAt()->format(DateTime::FORMAT))->toBe('2021-12-25T15:00:00.000000Z');
    \expect($event->getType())->toBe('payment_settled');

    $paymentSource = $event->getPaymentSource();
    \expect($paymentSource)->toBeInstanceOf(PaymentSource::class);
    \expect($paymentSource->getAccountHolderName())->toBe('HOLDER NAME');
    \expect($paymentSource->getId())->toBe('1f111d3c-9427-43be-1111-1111219d111c');

    /** @var ScanDetailsInterface $scan */
    $scan = $paymentSource->getAccountIdentifiers()[0];
    \expect($scan)->toBeInstanceOf(ScanDetailsInterface::class);
    \expect($scan->getAccountNumber())->toBe('00000111');
    \expect($scan->getSortCode())->toBe('111111');
    \expect($scan->getType())->toBe('sort_code_account_number');

    /** @var IbanDetailsInterface $iban */
    $iban = $paymentSource->getAccountIdentifiers()[1];
    \expect($iban)->toBeInstanceOf(IbanDetailsInterface::class);
    \expect($iban->getType())->toBe('iban');
    \expect($iban->getIban())->toBe('GB11CLRB01011100000111');
});

\it('handles payment failed', function () {
    /** @var PaymentFailedEventInterface $event */
    $event = null;

    \webhook(WebhookPayload::paymentFailed())->handler(function (PaymentFailedEventInterface $evt) use (&$event) {
        $event = $evt;
    })->execute();

    \expect($event)->toBeInstanceOf(PaymentFailedEventInterface::class);
    \expect($event->getFailedAt()->format(DateTime::FORMAT))->toBe('2021-12-25T15:00:00.000000Z');
    \expect($event->getFailureStage())->toBe('authorizing');
    \expect($event->getFailureReason())->toBe('provider_rejected');
    \expect($event->getType())->toBe('payment_failed');
});

\it('handles settlement risk category', function (string $body) {
    $event = null;

    \webhook($body)->handler(function (PaymentEventInterface $evt) use (&$event) {
        $event = $evt;
    })->execute();

    \expect($event->getSettlementRiskCategory())->toBe('low_risk');
})->with([
    'executed' => WebhookPayload::paymentExecuted(),
    'settled' => WebhookPayload::paymentSettled(),
]);

\it('handles bank transfer payment method', function (string $body) {
    /** @var PaymentExecutedEventInterface $event */
    $event = null;

    // bank transfer
    \webhook($body)->handler(function (PaymentEventInterface $evt) use (&$event) {
        $event = $evt;
    })->execute();

    /** @var BankTransferPaymentMethodInterface $paymentMethod */
    $paymentMethod = $event->getPaymentMethod();
    \expect($paymentMethod)->toBeInstanceOf(BankTransferPaymentMethodInterface::class);
    \expect($paymentMethod->getType())->toBe('bank_transfer');
    \expect($paymentMethod->getProviderId())->toBe('mock-payments-gb-redirect');
    \expect($paymentMethod->getSchemeId())->toBe('faster_payments_service');
})->with([
    'executed' => WebhookPayload::paymentExecuted(),
    'settled' => WebhookPayload::paymentSettled(),
    'failed' => WebhookPayload::paymentFailed(),
]);

\it('handles mandate payment method', function () {
    /** @var PaymentExecutedEventInterface $event */
    $event = null;

    \webhook(WebhookPayload::paymentExecutedMandate())->handler(function (PaymentExecutedEventInterface $evt) use (&$event) {
        $event = $evt;
    })->execute();

    /** @var MandatePaymentMethodInterface $paymentMethod */
    $paymentMethod = $event->getPaymentMethod();

    \expect($paymentMethod)->toBeInstanceOf(MandatePaymentMethodInterface::class);
    \expect($paymentMethod->getType())->toBe('mandate');
    \expect($paymentMethod->getMandateId())->toBe('d65f3521-fa55-44fc-9a75-ba43456de7f2');
});
