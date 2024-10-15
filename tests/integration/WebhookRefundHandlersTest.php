<?php

declare(strict_types=1);

use TrueLayer\Constants\DateTime;
use TrueLayer\Interfaces\Webhook\RefundEventInterface;
use TrueLayer\Interfaces\Webhook\RefundExecutedEventInterface;
use TrueLayer\Interfaces\Webhook\RefundFailedEventInterface;
use TrueLayer\Tests\Integration\Mocks\WebhookPayload;

\it('handles common refund data', function (string $body) {
    /** @var RefundEventInterface $event */
    $event = null;

    \webhook($body)->handler(function (RefundEventInterface $evt) use (&$event) {
        $event = $evt;
    })->execute();

    \expect($event)->toBeInstanceOf(RefundEventInterface::class);
    \expect($event->getEventId())->toBe('f6321c84-1797-4e66-acd4-d768c09f9edf');
    \expect($event->getEventVersion())->toBe(1);
    \expect($event->getPaymentId())->toBe('dfb531ca-8e25-4753-bc23-0c7eeb8d4f29');
    \expect($event->getRefundId())->toBe('af386a24-e5e6-4508-a4e4-82d4bc4e3677');
    \expect($event->getTimestamp()->format(DateTime::FORMAT))->toBe('2022-02-16T16:21:14.000000Z');
    \expect($event->getBody())->toHaveKey('event_id');
})->with([
    'executed' => WebhookPayload::refundExecuted(),
    'failed' => WebhookPayload::refundFailed(),
]);

\it('handles refund executed', function () {
    /** @var RefundExecutedEventInterface $event */
    $event = null;

    \webhook(WebhookPayload::refundExecuted())->handler(function (RefundExecutedEventInterface $evt) use (&$event) {
        $event = $evt;
    })->execute();

    \expect($event)->toBeInstanceOf(RefundExecutedEventInterface::class);
    \expect($event->getExecutedAt()->format(DateTime::FORMAT))->toBe('2021-12-25T15:00:00.000000Z');
    \expect($event->getType())->toBe('refund_executed');
});

\it('handles refund failed', function () {
    /** @var RefundFailedEventInterface $event */
    $event = null;

    \webhook(WebhookPayload::refundFailed())->handler(function (RefundFailedEventInterface $evt) use (&$event) {
        $event = $evt;
    })->execute();

    \expect($event)->toBeInstanceOf(RefundFailedEventInterface::class);
    \expect($event->getFailedAt()->format(DateTime::FORMAT))->toBe('2021-12-25T15:00:00.000000Z');
    \expect($event->getFailureReason())->toBe('insufficient_funds');
    \expect($event->getType())->toBe('refund_failed');
});

\it('handles refund webhook metadata', function (string $body, array $metadata) {
    /** @var RefundEventInterface $event */
    $event = null;

    \webhook($body)->handler(function (RefundEventInterface $evt) use (&$event) {
        $event = $evt;
    })->execute();

    \expect($event)->toBeInstanceOf(RefundEventInterface::class);
    \expect($event->getMetadata())->toBe($metadata);
})->with([
    'executed, no metadata' => [WebhookPayload::refundExecuted(), []],
    'executed, with metadata' => [WebhookPayload::refundExecutedWithMetadata(), ['foo' => 'bar']],
    'failed, no metadata' => [WebhookPayload::refundFailed(), []],
    'failed, with metadata' => [WebhookPayload::refundFailedWithMetadata(), ['foo' => 'bar']],
]);
