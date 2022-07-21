<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Webhook;

use ReflectionException;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Exceptions\ValidationException;
use TrueLayer\Exceptions\WebhookHandlerException;
use TrueLayer\Exceptions\WebhookHandlerInvalidArgumentException;

interface WebhookInterface
{
    /**
     * @param callable|class-string $handler
     * @return WebhookInterface
     * @throws ReflectionException
     * @throws WebhookHandlerInvalidArgumentException
     * @throws WebhookHandlerException
     */
    public function handler($handler): WebhookInterface;

    /**
     * @param callable|class-string ...$handlers
     * @return WebhookInterface
     * @throws ReflectionException
     * @throws WebhookHandlerInvalidArgumentException
     * @throws WebhookHandlerException
     */
    public function handlers(...$handlers): WebhookInterface;

    /**
     * @param string $path
     * @return WebhookInterface
     */
    public function path(string $path): WebhookInterface;

    /**
     * @param string $body
     * @return WebhookInterface
     */
    public function body(string $body): WebhookInterface;

    /**
     * @param mixed[] $headers
     * @return WebhookInterface
     * @throws WebhookHandlerInvalidArgumentException
     */
    public function headers(array $headers): WebhookInterface;

    /**
     * @throws WebhookHandlerInvalidArgumentException
     * @throws InvalidArgumentException
     * @throws ValidationException
     */
    public function execute(): void;
}
