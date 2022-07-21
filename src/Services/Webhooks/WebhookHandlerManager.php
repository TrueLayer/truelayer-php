<?php

declare(strict_types=1);

namespace TrueLayer\Services\Webhooks;

use Closure;
use Illuminate\Support\Arr;
use ReflectionClass;
use ReflectionException;
use ReflectionFunction;
use ReflectionNamedType;
use TrueLayer\Exceptions\WebhookHandlerException;
use TrueLayer\Exceptions\WebhookHandlerInvalidArgumentException;
use TrueLayer\Interfaces\Webhook\EventInterface;
use TrueLayer\Interfaces\Webhook\WebhookHandlerManagerInterface;

class WebhookHandlerManager implements WebhookHandlerManagerInterface
{
    /**
     * @var array<string, Closure[]>
     */
    private array $handlers = [];

    /**
     * @param callable|class-string $handler
     *
     * @throws ReflectionException
     * @throws WebhookHandlerException
     * @throws WebhookHandlerInvalidArgumentException
     */
    public function add($handler): void
    {
        if (\is_string($handler) && \class_exists($handler)) {
            $handler = $this->instantiateHandler($handler);
        }

        if (!\is_callable($handler)) {
            throw new WebhookHandlerException('The provided webhook handler is not callable');
        }

        $closure = Closure::fromCallable($handler);
        $type = $this->getHandlerParameterType($closure);

        $typeHandlers = $this->handlers[$type] ?? [];
        $typeHandlers[] = $closure;
        $this->handlers[$type] = $typeHandlers;
    }

    /**
     * @param callable|class-string ...$handlers
     *
     * @throws WebhookHandlerException
     * @throws WebhookHandlerInvalidArgumentException
     * @throws ReflectionException
     *
     * @return void
     */
    public function addMany(...$handlers): void
    {
        foreach ($handlers as $handler) {
            $this->add($handler);
        }
    }

    /**
     * @param EventInterface $event
     */
    public function execute(EventInterface $event): void
    {
        $handlers = $this->getFor($event);

        foreach ($handlers as $handler) {
            $handler($event);
        }
    }

    /**
     * @param EventInterface $event
     *
     * @return Closure[]
     */
    private function getFor(EventInterface $event): array
    {
        $interfaces = \class_implements($event);
        if (!$interfaces) {
            return [];
        }

        $interfaces = \array_unique($interfaces);
        $handlers = \array_map(fn ($interface) => $this->handlers[$interface] ?? [], $interfaces);

        return Arr::flatten($handlers);
    }

    /**
     * @param Closure $handler
     *
     * @throws WebhookHandlerInvalidArgumentException
     * @throws ReflectionException
     *
     * @return class-string
     */
    private function getHandlerParameterType(Closure $handler): string
    {
        $ref = new ReflectionFunction($handler);
        $parameters = $ref->getParameters();

        if (\count($parameters) !== 1) {
            throw new WebhookHandlerInvalidArgumentException('Webhook handler function signature expects single argument');
        }

        $type = $parameters[0]->getType();
        $typeName = $type instanceof ReflectionNamedType ? $type->getName() : null;

        if (!$typeName || !\is_subclass_of($typeName, EventInterface::class) && $typeName !== EventInterface::class) {
            throw new WebhookHandlerInvalidArgumentException('Webhook handler argument should be of type ' . EventInterface::class);
        }

        return $typeName;
    }

    /**
     * If a handler name is provided instead of an instance, we instantiate it.
     *
     * @param class-string $class
     *
     * @throws WebhookHandlerException
     * @throws ReflectionException
     *
     * @return mixed
     */
    private function instantiateHandler(string $class)
    {
        $constructor = (new ReflectionClass($class))->getConstructor();

        if ($constructor && $constructor->getNumberOfParameters() > 0) {
            throw new WebhookHandlerException("Could not instantiate webhook handler: {$class}");
        }

        return new $class();
    }
}
