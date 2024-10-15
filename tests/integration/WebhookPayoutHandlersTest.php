<?php

declare(strict_types=1);

use TrueLayer\Constants\DateTime;
use TrueLayer\Interfaces\Webhook\Beneficiary\BusinessAccountBeneficiaryInterface;
use TrueLayer\Interfaces\Webhook\Beneficiary\PaymentSourceBeneficiaryInterface;
use TrueLayer\Interfaces\Webhook\PayoutEventInterface;
use TrueLayer\Interfaces\Webhook\PayoutExecutedEventInterface;
use TrueLayer\Interfaces\Webhook\PayoutFailedEventInterface;
use TrueLayer\Tests\Integration\Mocks\WebhookPayload;

\it('handles common payout data', function (string $body) {
    /** @var PayoutEventInterface $event */
    $event = null;

    \webhook($body)->handler(function (PayoutEventInterface $evt) use (&$event) {
        $event = $evt;
    })->execute();

    \expect($event)->toBeInstanceOf(PayoutEventInterface::class);

    \expect($event->getEventId())->toBe('b8d4dda0-ff2c-4d77-a6da-4615e4bad941');
    \expect($event->getEventVersion())->toBe(1);
    \expect($event->getPayoutId())->toBe('0cd1b0f7-71bc-4d24-b209-95259dadcc20');
    \expect($event->getTimestamp()->format(DateTime::FORMAT))->toBe('2022-02-16T16:21:14.000000Z');
    \expect($event->getBeneficiary())->toBeNull();
    \expect($event->getBody())->toHaveKey('event_id');
})->with([
    'executed' => WebhookPayload::payoutExecuted(),
    'failed' => WebhookPayload::payoutFailed(),
]);

\it('handles payout executed', function () {
    /** @var PayoutExecutedEventInterface $event */
    $event = null;

    \webhook(WebhookPayload::payoutExecuted())->handler(function (PayoutExecutedEventInterface $evt) use (&$event) {
        $event = $evt;
    })->execute();

    \expect($event)->toBeInstanceOf(PayoutExecutedEventInterface::class);
    \expect($event->getExecutedAt()->format(DateTime::FORMAT))->toBe('2021-12-25T15:00:00.000000Z');
    \expect($event->getType())->toBe('payout_executed');
});

\it('handles payout failed', function () {
    /** @var PayoutFailedEventInterface $event */
    $event = null;

    \webhook(WebhookPayload::payoutFailed())->handler(function (PayoutFailedEventInterface $evt) use (&$event) {
        $event = $evt;
    })->execute();

    \expect($event)->toBeInstanceOf(PayoutFailedEventInterface::class);
    \expect($event->getFailedAt()->format(DateTime::FORMAT))->toBe('2021-12-25T15:00:00.000000Z');
    \expect($event->getFailureReason())->toBe('scheme_error');
    \expect($event->getType())->toBe('payout_failed');
});

\it('handles closed loop payouts', function (string $body) {
    /** @var PayoutEventInterface $event */
    $event = null;

    \webhook($body)->handler(function (PayoutEventInterface $evt) use (&$event) {
        $event = $evt;
    })->execute();

    /** @var PaymentSourceBeneficiaryInterface $beneficiary */
    $beneficiary = $event->getBeneficiary();
    \expect($beneficiary)->toBeInstanceOf(PaymentSourceBeneficiaryInterface::class);
    \expect($beneficiary->getType())->toBe('payment_source');
    \expect($beneficiary->getUserId())->toBe('a0977be8-c406-4f75-bb81-b5ca0689b29b');
    \expect($beneficiary->getPaymentSourceId())->toBe('4a59c822-3bfb-42ba-9202-b6d89988a195');
})->with([
    'executed' => WebhookPayload::payoutExecutedClosedLoop(),
    'failed' => WebhookPayload::payoutFailedClosedLoop(),
]);

\it('handles business account payouts', function (string $body) {
    /** @var PayoutEventInterface $event */
    $event = null;

    \webhook($body)->handler(function (PayoutEventInterface $evt) use (&$event) {
        $event = $evt;
    })->execute();

    /** @var BusinessAccountBeneficiaryInterface $beneficiary */
    $beneficiary = $event->getBeneficiary();
    \expect($beneficiary)->toBeInstanceOf(BusinessAccountBeneficiaryInterface::class);
    \expect($beneficiary->getType())->toBe('business_account');
})->with([
    'executed' => WebhookPayload::payoutExecutedBusinessAccount(),
    'failed' => WebhookPayload::payoutFailedBusinessAccount(),
]);

\it('handles payout webhook metadata', function (string $body, array $metadata) {
    /** @var PayoutEventInterface $event */
    $event = null;

    \webhook($body)->handler(function (PayoutEventInterface $evt) use (&$event) {
        $event = $evt;
    })->execute();

    \expect($event)->toBeInstanceOf(PayoutEventInterface::class);
    \expect($event->getMetadata())->toBe($metadata);
})->with([
    'executed, no metadata' => [WebhookPayload::payoutExecuted(), []],
    'executed, with metadata' => [WebhookPayload::payoutExecutedWithMetadata(), ['foo' => 'bar']],
    'failed, no metadata' => [WebhookPayload::payoutFailed(), []],
    'failed, with metadata' => [WebhookPayload::payoutFailedWithMetadata(), ['foo' => 'bar']],
]);
