<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Webhook;

use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Exceptions\WebhookHandlerException;
use TrueLayer\Exceptions\WebhookHandlerInvalidArgumentException;

interface WebhookInterface
{
    /**
     * @param callable|class-string $handler
     *
     * @throws WebhookHandlerInvalidArgumentException
     * @throws WebhookHandlerException
     * @throws \ReflectionException
     *
     * @return WebhookInterface
     */
    public function handler($handler): WebhookInterface;

    /**
     * @param callable|class-string ...$handlers
     *
     * @throws WebhookHandlerInvalidArgumentException
     * @throws WebhookHandlerException
     * @throws \ReflectionException
     *
     * @return WebhookInterface
     */
    public function handlers(...$handlers): WebhookInterface;

    /**
     * @param string $path
     *
     * @return WebhookInterface
     */
    public function path(string $path): WebhookInterface;

    /**
     * @param string $body
     *
     * @return WebhookInterface
     */
    public function body(string $body): WebhookInterface;

    /**
     * @param mixed[] $headers
     *
     * @throws WebhookHandlerInvalidArgumentException
     *
     * @return WebhookInterface
     */
    public function headers(array $headers): WebhookInterface;

    /**
     * @throws WebhookHandlerInvalidArgumentException
     * @throws InvalidArgumentException
     */
    public function execute(): void;
}
