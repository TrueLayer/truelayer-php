<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Webhook;

use ReflectionException;
use TrueLayer\Exceptions\WebhookHandlerException;
use TrueLayer\Exceptions\WebhookHandlerInvalidArgumentException;

interface WebhookHandlerManagerInterface
{
    /**
     * @param callable|class-string $handler
     *
     * @throws ReflectionException
     * @throws WebhookHandlerException
     * @throws WebhookHandlerInvalidArgumentException
     */
    public function add($handler): void;

    /**
     * @param callable|class-string ...$handlers
     *
     * @throws ReflectionException
     * @throws WebhookHandlerException
     * @throws WebhookHandlerInvalidArgumentException
     *
     * @return void
     */
    public function addMany(...$handlers): void;

    /**
     * @param EventInterface $event
     */
    public function execute(EventInterface $event): void;
}
