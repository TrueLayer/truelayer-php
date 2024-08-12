<?php

declare(strict_types=1);

use TrueLayer\Exceptions\WebhookVerificationFailedException;
use TrueLayer\Interfaces\Webhook\EventInterface;
use TrueLayer\Interfaces\Webhook\PaymentEventInterface;
use TrueLayer\Interfaces\Webhook\PaymentExecutedEventInterface;
use TrueLayer\Tests\Integration\Mocks\Signing;
use TrueLayer\Tests\Integration\Mocks\WebhookHandler;
use TrueLayer\Tests\Integration\Mocks\WebhookPayload;

\it('calls default handler if webhook type is unknown', function () {
    $handler = new WebhookHandler();

    \webhook(WebhookPayload::unknownType())
        ->handler($handler)
        ->execute();

    \expect($handler->event->getEventId())->toBe('b8d4dda0-ff2c-4d77-a6da-4615e4bad941');
    \expect($handler->event->getEventVersion())->toBe(1);
    \expect($handler->event->getType())->toBe('foo');
});

\it('calls default handler if webhook type is known, but validation fails', function () {
    $handler = new WebhookHandler();

    \webhook(WebhookPayload::unknownSubType())
        ->handler($handler)
        ->execute();

    \expect($handler->event->getEventId())->toBe('b8d4dda0-ff2c-4d77-a6da-4615e4bad941');
    \expect($handler->event->getEventVersion())->toBe(1);
    \expect($handler->event->getType())->toBe('payment_executed');
});

\it('calls all handlers for an event type', function () {
    /** @var EventInterface $event1 */
    $defaultEvent1 = $defaultEvent2 = $paymentEvent1 = $paymentEvent2 = $executedEvent1 = $executedEvent2 = null;

    \webhook(WebhookPayload::paymentExecuted())
        ->handler(function (EventInterface $event) use (&$defaultEvent1) {
            $defaultEvent1 = $event;
        })
        ->handler(function (EventInterface $event) use (&$defaultEvent2) {
            $defaultEvent2 = $event;
        })
        ->handler(function (PaymentEventInterface $event) use (&$paymentEvent1) {
            $paymentEvent1 = $event;
        })
        ->handler(function (PaymentEventInterface $event) use (&$paymentEvent2) {
            $paymentEvent2 = $event;
        })
        ->handler(function (PaymentExecutedEventInterface $event) use (&$executedEvent1) {
            $executedEvent1 = $event;
        })
        ->handler(function (PaymentExecutedEventInterface $event) use (&$executedEvent2) {
            $executedEvent2 = $event;
        })
        ->execute();

    \expect($defaultEvent1)->toBeInstanceOf(PaymentExecutedEventInterface::class);
    \expect($defaultEvent1->getEventId())->toBe('b8d4dda0-ff2c-4d77-a6da-4615e4bad941');
    \expect($defaultEvent1->getEventVersion())->toBe(1);
    \expect($defaultEvent1->getType())->toBe('payment_executed');

    \expect(
        $defaultEvent1 === $defaultEvent2
        && $defaultEvent1 === $paymentEvent1
        && $defaultEvent1 === $paymentEvent2
        && $defaultEvent1 === $executedEvent1
        && $defaultEvent1 === $executedEvent2
    )->toBeTrue();
});

\it('works with globals', function () {
    $_POST = \json_decode(WebhookPayload::paymentExecuted());
    $_SERVER['REQUEST_URI'] = '/test';
    $_SERVER['HTTP_CUSTOM_HEADER'] = 'test';
    $_SERVER['CONTENT_TYPE'] = 'application/json';
    $_SERVER['HTTP_TL_SIGNATURE'] = Signing::sign(\json_encode($_POST), '/test', [
        'custom-header' => 'test',
        'content-type' => 'application/json',
    ]);
    $_SERVER['HTTP_X_TL_Webhook_Timestamp'] = '2020-05-18T10:17:47Z';

    \rawClient([Signing::getPublicKeysResponse()])->create()->webhook()->execute();

    // If we reach this point, no exception was thrown so signature verification was successful
    \expect(true)->toBeTrue();
});

\it('allows globals to be overridden', function () {
    $_POST = \json_decode(WebhookPayload::unknownType());
    $_SERVER['REQUEST_URI'] = '/foo';
    $_SERVER['HTTP_CUSTOM_HEADER'] = 'foo';
    $_SERVER['CONTENT_TYPE'] = 'application/json';
    $_SERVER['HTTP_TL_SIGNATURE'] = Signing::sign(\json_encode($_POST), '/foo', [
        'content-type' => 'application/json',
    ]);
    $_SERVER['HTTP_X_TL_Webhook_Timestamp'] = '2020-05-18T10:17:47Z';

    $handler = new WebhookHandler();

    // The webhook helper does not use globals, instead it provides
    // path, body, and headers directly
    \webhook(WebhookPayload::paymentExecuted())
        ->handler($handler)
        ->execute();

    // We expect the event to be payment executed rather than the unknown type set on the globals.
    \expect($handler->event)->toBeInstanceOf(PaymentExecutedEventInterface::class);
});

\it('only calls matching handlers', function () {
    $default = new WebhookHandler();
    $refund = null;

    \webhook(WebhookPayload::paymentExecuted())
        ->handler($default)
        ->handler(function (TrueLayer\Entities\Webhook\RefundEvent $event) use (&$refund) {
            $refund = $event;
        })
        ->execute();

    \expect($default->event)->toBeInstanceOf(PaymentExecutedEventInterface::class);
    \expect($refund)->toBeNull();
});

\it('works with invokable classes', function () {
    $handler = new WebhookHandler();

    \webhook(WebhookPayload::paymentExecuted())
        ->handler($handler)
        ->execute();

    \expect($handler->event)->toBeInstanceOf(PaymentExecutedEventInterface::class);
    \expect($handler->event->getEventId())->toBe('b8d4dda0-ff2c-4d77-a6da-4615e4bad941');
    \expect($handler->event->getEventVersion())->toBe(1);
    \expect($handler->event->getType())->toBe('payment_executed');
});

\it('works with invokable class names', function () {
    class WebhookHandlerTest
    {
        public static EventInterface $event;

        public function __invoke(EventInterface $event)
        {
            self::$event = $event;
        }
    }

    class SecondWebhookHandlerTest
    {
        public static EventInterface $event;

        public function __invoke(EventInterface $event)
        {
            self::$event = $event;
        }
    }

    \webhook(WebhookPayload::paymentExecuted())
        ->handlers(
            WebhookHandlerTest::class,
            SecondWebhookHandlerTest::class
        )
        ->execute();

    \expect(WebhookHandlerTest::$event)->toBeInstanceOf(PaymentExecutedEventInterface::class);
    \expect(SecondWebhookHandlerTest::$event)->toEqual(WebhookHandlerTest::$event);
});

\it('works with paths with trailing slash', function () {
    $body = WebhookPayload::paymentExecuted();
    $signedPath = '/path';
    $verifiedPath = '/path';
    $headers = Signing::getHeaders($body, $signedPath);

    \rawClient([Signing::getPublicKeysResponse()])->create()
        ->webhook()
        ->body($body)
        ->path($verifiedPath)
        ->headers($headers)
        ->execute();

    // If we reach this point, no exception was thrown so signature verification was successful
    \expect(true)->toBeTrue();
});

\it('works with mixed case headers', function () {
    $body = WebhookPayload::paymentExecuted();

    $signedHeaders = [
        'x-tl-webhook-timestamp' => '2022-02-16T16:21:14Z',
        'custom-header' => 'VALUE',
    ];

    $signature = Signing::sign($body, Signing::getPath(), $signedHeaders);

    $verifiedHeaders = [
        'X-TL-WEBHOOK-TIMESTAMP' => '2022-02-16T16:21:14Z',
        'CUSTOM-HEADER' => 'VALUE',
        'TL-SIGNATURE' => $signature,
    ];

    \rawClient([Signing::getPublicKeysResponse()])->create()
        ->webhook()
        ->body($body)
        ->path(Signing::getPath())
        ->headers($verifiedHeaders)
        ->execute();

    // If we reach this point, no exception was thrown so signature verification was successful
    \expect(true)->toBeTrue();
});

\it('does not call handlers and throws exception if signature is not valid', function () {
    /** @var EventInterface $event1 */
    $defaultEvent = $paymentEvent = $executedEvent = null;
    $thrownInvalidSignature = false;

    $body = WebhookPayload::paymentExecuted();

    $signedHeaders = [
        'x-tl-webhook-timestamp' => '2022-02-16T16:21:14Z',
    ];

    $signature = Signing::sign($body, Signing::getPath(), $signedHeaders);

    $verifiedHeaders = [
        'x-tl-webhook-timestamp' => '2023-02-16T16:21:14Z', // Different timestamp
        'TL-SIGNATURE' => $signature,
    ];

    try {
        \rawClient([Signing::getPublicKeysResponse()])->create()
            ->webhook()
            ->body($body)
            ->path(Signing::getPath())
            ->headers($verifiedHeaders)
            ->handler(function (EventInterface $event) use (&$defaultEvent) {
                $defaultEvent = $event;
            })
            ->handler(function (PaymentEventInterface $event) use (&$paymentEvent) {
                $paymentEvent = $event;
            })
            ->handler(function (PaymentExecutedEventInterface $event) use (&$executedEvent) {
                $executedEvent = $event;
            })
            ->execute();
    } catch (WebhookVerificationFailedException $e) {
        $thrownInvalidSignature = true;
    }

    \expect($thrownInvalidSignature)->toBeTrue();
    \expect($defaultEvent)->toBeNull();
    \expect($paymentEvent)->toBeNull();
    \expect($executedEvent)->toBeNull();
});
