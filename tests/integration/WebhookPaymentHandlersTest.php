<?php

declare(strict_types=1);

use TrueLayer\Constants\DateTime;
use TrueLayer\Interfaces\AccountIdentifier\IbanDetailsInterface;
use TrueLayer\Interfaces\AccountIdentifier\ScanDetailsInterface;
use TrueLayer\Interfaces\Payment\PaymentSourceInterface;
use TrueLayer\Interfaces\Webhook\PaymentAuthorizedEventInterface;
use TrueLayer\Interfaces\Webhook\PaymentCreditableEventInterface;
use TrueLayer\Interfaces\Webhook\PaymentEventInterface;
use TrueLayer\Interfaces\Webhook\PaymentExecutedEventInterface;
use TrueLayer\Interfaces\Webhook\PaymentFailedEventInterface;
use TrueLayer\Interfaces\Webhook\PaymentMethod\BankTransferPaymentMethodInterface;
use TrueLayer\Interfaces\Webhook\PaymentMethod\MandatePaymentMethodInterface;
use TrueLayer\Interfaces\Webhook\PaymentSettledEventInterface;
use TrueLayer\Interfaces\Webhook\PaymentSettlementStalledEventInterface;
use TrueLayer\Tests\Integration\Mocks\WebhookPayload;

class ConfirmCallbackRan extends \Exception
{
}

\it('handles common payment data', function (string $body) {
    \webhook($body)->handler(function (PaymentEventInterface $event) {
        \expect($event)
            ->getType()->toBeString()
            ->getEventId()->toBe('b8d4dda0-ff2c-4d77-a6da-4615e4bad941')
            ->getEventVersion()->toBe(1)
            ->getPaymentId()->toBe('60c0a60ed8d7-4e5b-ac79-401b1d8a8633')
            ->getMetadata()->toMatchArray([
                'key1' => 'value1',
                'key2' => 'value2',
            ])
            ->getBody()->event_id->toBe('b8d4dda0-ff2c-4d77-a6da-4615e4bad941');

        \expect($event->getTimestamp()->format(DateTime::FORMAT))->toBe('2022-02-16T16:21:14.000000Z');

        throw new ConfirmCallbackRan();
    })->execute();
})->with([
    'authorized' => WebhookPayload::paymentAuthorized(),
    'executed' => WebhookPayload::paymentExecuted(),
    'settled' => WebhookPayload::paymentSettled(),
    'failed' => WebhookPayload::paymentFailed(),
    'creditable' => WebhookPayload::paymentCreditable(),
    'settlement_stalled' => WebhookPayload::paymentSettlementStalled(),
])->throws(ConfirmCallbackRan::class);

\it('handles empty metadata', function () {
    \webhook(WebhookPayload::paymentNoMetadata())->handler(function (PaymentEventInterface $event) {
        \expect($event)
            ->getMetadata()->toBeArray()
            ->getMetadata()->toBeEmpty();
        throw new ConfirmCallbackRan();
    })->execute();
})->throws(ConfirmCallbackRan::class);

\it('handles payment source', function (string $body) {
    \webhook($body)->handler(function (PaymentEventInterface $event) {
        /** @var PaymentAuthorizedEventInterface|PaymentExecutedEventInterface|PaymentSettledEventInterface|PaymentFailedEventInterface $event */

        $paymentSource = $event->getPaymentSource();
        \expect($paymentSource)->toBeInstanceOf(PaymentSourceInterface::class)
            ->getAccountHolderName()->toBe('HOLDER NAME')
            ->getId()->toBe('1f111d3c-9427-43be-1111-1111219d111c');

        /** @var ScanDetailsInterface $scan */
        $scan = $paymentSource->getAccountIdentifiers()[0];
        \expect($scan)
            ->toBeInstanceOf(ScanDetailsInterface::class)
            ->getAccountNumber()->toBe('00000111')
            ->getSortCode()->toBe('111111')
            ->getType()->toBe('sort_code_account_number');

        /** @var IbanDetailsInterface $iban */
        $iban = $paymentSource->getAccountIdentifiers()[1];
        \expect($iban)
            ->toBeInstanceOf(IbanDetailsInterface::class)
            ->getType()->toBe('iban')
            ->getIban()->toBe('GB11CLRB01011100000111');

        throw new ConfirmCallbackRan();
    })->execute();
})->with([
    'authorized' => WebhookPayload::paymentAuthorized(),
    'executed' => WebhookPayload::paymentExecuted(),
    'settled' => WebhookPayload::paymentSettled(),
    'failed' => WebhookPayload::paymentFailed(),
])->throws(ConfirmCallbackRan::class);

\it('handles bank transfer payment method', function (string $body) {
    \webhook($body)->handler(function (PaymentEventInterface $event) {
        /** @var BankTransferPaymentMethodInterface $paymentMethod */
        $paymentMethod = $event->getPaymentMethod();
        \expect($paymentMethod)
            ->toBeInstanceOf(BankTransferPaymentMethodInterface::class)
            ->getType()->toBe('bank_transfer')
            ->getProviderId()->toBe('mock-payments-gb-redirect')
            ->getSchemeId()->toBe('faster_payments_service');

        throw new ConfirmCallbackRan();
    })->execute();
})->with([
    'authorized' => WebhookPayload::paymentAuthorized(),
    'executed' => WebhookPayload::paymentExecuted(),
    'settled' => WebhookPayload::paymentSettled(),
    'failed' => WebhookPayload::paymentFailed(),
])->throws(ConfirmCallbackRan::class);

\it('handles mandate payment method', function () {
    \webhook(WebhookPayload::paymentExecutedMandate())->handler(function (PaymentExecutedEventInterface $event) {
        /** @var MandatePaymentMethodInterface $paymentMethod */
        $paymentMethod = $event->getPaymentMethod();

        \expect($paymentMethod)
            ->toBeInstanceOf(MandatePaymentMethodInterface::class)
            ->getType()->toBe('mandate')
            ->getMandateId()->toBe('d65f3521-fa55-44fc-9a75-ba43456de7f2')
            ->getReference()->toBe('test');

        throw new ConfirmCallbackRan();
    })->execute();
})->throws(ConfirmCallbackRan::class);

\it('handles settlement risk category', function (string $body) {
    \webhook($body)->handler(function (PaymentEventInterface $event) {
        \expect($event)->getSettlementRiskCategory()->toBe('low_risk');

        throw new ConfirmCallbackRan();
    })->execute();
})->with([
    'executed' => WebhookPayload::paymentExecuted(),
    'settled' => WebhookPayload::paymentSettled(),
])->throws(ConfirmCallbackRan::class);

\it('handles payment authorized', function () {
    \webhook(WebhookPayload::paymentAuthorized())->handler(function (PaymentAuthorizedEventInterface $event) {
        \expect($event)->getType()->toBe('payment_authorized');
        \expect($event->getAuthorizedAt()->format(DateTime::FORMAT))->toBe('2021-12-25T15:00:00.000000Z');

        throw new ConfirmCallbackRan();
    })->execute();
})->throws(ConfirmCallbackRan::class);

\it('handles payment executed', function () {
    \webhook(WebhookPayload::paymentExecuted())->handler(function (PaymentExecutedEventInterface $event) {
        \expect($event)->getType()->toBe('payment_executed');
        \expect($event->getExecutedAt()->format(DateTime::FORMAT))->toBe('2021-12-25T15:00:00.000000Z');

        throw new ConfirmCallbackRan();
    })->execute();
})->throws(ConfirmCallbackRan::class);

\it('handles payment settled', function () {
    \webhook(WebhookPayload::paymentSettled())->handler(function (PaymentSettledEventInterface $event) {
        \expect($event)->getType()->toBe('payment_settled');

        \expect($event->getSettledAt()->format(DateTime::FORMAT))->toBe('2021-12-25T15:00:00.000000Z');

        $paymentSource = $event->getPaymentSource();
        \expect($event->getPaymentSource())->toBeInstanceOf(PaymentSourceInterface::class)
            ->getAccountHolderName()->toBe('HOLDER NAME')
            ->getId()->toBe('1f111d3c-9427-43be-1111-1111219d111c');

        /** @var ScanDetailsInterface $scan */
        $scan = $paymentSource->getAccountIdentifiers()[0];
        \expect($scan)
            ->toBeInstanceOf(ScanDetailsInterface::class)
            ->getAccountNumber()->toBe('00000111')
            ->getSortCode()->toBe('111111')
            ->getType()->toBe('sort_code_account_number');

        /** @var IbanDetailsInterface $iban */
        $iban = $paymentSource->getAccountIdentifiers()[1];
        \expect($iban)
            ->toBeInstanceOf(IbanDetailsInterface::class)
            ->getType()->toBe('iban')
            ->getIban()->toBe('GB11CLRB01011100000111');

        throw new ConfirmCallbackRan();
    })->execute();
})->throws(ConfirmCallbackRan::class);

\it('handles payment failed', function () {
    \webhook(WebhookPayload::paymentFailed())->handler(function (PaymentFailedEventInterface $event) {
        \expect($event)
            ->getType()->toBe('payment_failed')
            ->getFailureStage()->toBe('authorizing')
            ->getFailureReason()->toBe('provider_rejected');

        \expect($event->getFailedAt()->format(DateTime::FORMAT))->toBe('2021-12-25T15:00:00.000000Z');

        throw new ConfirmCallbackRan();
    })->execute();
})->throws(ConfirmCallbackRan::class);

\it('handles payment creditable', function () {
    \webhook(WebhookPayload::paymentCreditable())->handler(function (PaymentCreditableEventInterface $event) {
        \expect($event)->getType()->toBe('payment_creditable');
        \expect($event->getCreditableAt()->format(DateTime::FORMAT))->toBe('2024-10-14T14:02:26.825000Z');

        throw new ConfirmCallbackRan();
    })->execute();
})->throws(ConfirmCallbackRan::class);

\it('handles settlement stalled', function () {
    \webhook(WebhookPayload::paymentSettlementStalled())->handler(function (PaymentSettlementStalledEventInterface $event) {
        \expect($event)->getType()->toBe('payment_settlement_stalled');
        \expect($event->getSettlementStalledAt()->format(DateTime::FORMAT))->toBe('2024-10-14T14:02:26.825000Z');

        throw new ConfirmCallbackRan();
    })->execute();
})->throws(ConfirmCallbackRan::class);
