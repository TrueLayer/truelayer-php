<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Webhook;

use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Exceptions\ValidationException;
use TrueLayer\Exceptions\WebhookHandlerInvalidArgumentException;

interface WebhookInterface
{
    /**
     * Add a webhook notification handler
     * @param callable $handler
     * @return WebhookInterface
     */
    public function handler(callable $handler): WebhookInterface;

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
     * @param array $headers
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
