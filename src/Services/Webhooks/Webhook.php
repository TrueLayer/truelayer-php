<?php

declare(strict_types=1);

namespace TrueLayer\Services\Webhooks;

use Exception;
use Illuminate\Support\Arr;
use ReflectionException;
use TrueLayer\Constants\CustomHeaders;
use TrueLayer\Entities\Webhook\Event;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Exceptions\ValidationException;
use TrueLayer\Exceptions\WebhookHandlerInvalidArgumentException;
use TrueLayer\Interfaces\Factories\EntityFactoryInterface;
use TrueLayer\Interfaces\Webhook\EventInterface;
use TrueLayer\Interfaces\Webhook\WebhookInterface;
use TrueLayer\Services\Util\FromGlobals;
use TrueLayer\Signing\Contracts\Verifier as VerifierInterface;

class Webhook implements WebhookInterface
{
    /**
     * @var VerifierInterface
     */
    private VerifierInterface $verifier;

    /**
     * @var EntityFactoryInterface
     */
    private EntityFactoryInterface $entityFactory;

    /**
     * @var string
     */
    private string $path;

    /**
     * @var string
     */
    private string $body;

    /**
     * @var array
     */
    private array $headers;

    /**
     * @var array
     */
    private array $handlers = [];

    /**
     * @param VerifierInterface $verifier
     * @param EntityFactoryInterface $entityFactory
     */
    public function __construct(VerifierInterface $verifier, EntityFactoryInterface $entityFactory)
    {
        $this->verifier = $verifier;
        $this->entityFactory = $entityFactory;
    }

    /**
     * @param callable $handler
     * @return WebhookInterface
     * @throws WebhookHandlerInvalidArgumentException
     * @throws ReflectionException
     */
    public function handler(callable $handler): WebhookInterface
    {
        $closure = \Closure::fromCallable($handler);
        $ref = new \ReflectionFunction($closure);
        $parameters = $ref->getParameters();

        if (count($parameters) !== 1) {
            throw new WebhookHandlerInvalidArgumentException('Webhook handler function signature expects single argument');
        }

        $parameter = $parameters[0];
        $type = $parameter->getType()->getName();

        if (!is_subclass_of($type, EventInterface::class)) {
            throw new WebhookHandlerInvalidArgumentException('Webhook handler argument should be of type ' . EventInterface::class);
        }

        $typeHandlers = $this->handlers[$type] ?? [];
        $typeHandlers[] = $closure;
        $this->handlers[$type] = $typeHandlers;

        return $this;
    }

    /**
     * @param string $path
     * @return WebhookInterface
     */
    public function path(string $path): WebhookInterface
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @param string $body
     * @return WebhookInterface
     */
    public function body(string $body): WebhookInterface
    {
        $this->body = $body;
        return $this;
    }

    /**
     * @param array $headers
     * @return WebhookInterface
     */
    public function headers(array $headers): WebhookInterface
    {
        $this->headers = $headers;
        return $this;
    }

    /**
     * @throws WebhookHandlerInvalidArgumentException
     * @throws InvalidArgumentException
     * @throws ValidationException
     */
    public function execute(): void
    {
        $this->verify();

        $event = $this->getEventEntity();
        $handlers = $this->getEventHandlers($event);

        foreach ($handlers as $handler) {
            $handler($event);
        }
    }

    /**
     * @return string
     */
    private function getBody(): string
    {
        return $this->body ?? FromGlobals::getBody();
    }

    /**
     * @return array
     * @throws WebhookHandlerInvalidArgumentException
     */
    private function getDecodedBody(): array
    {
        $payload = json_decode($this->getBody(), true);

        if (!is_array($payload) || empty($payload) || json_last_error() !== JSON_ERROR_NONE) {
            throw new WebhookHandlerInvalidArgumentException('Body is empty or not in json format');
        }

        return $payload;
    }

    /**
     * @return array
     */
    private function getHeaders(): array
    {
        // The signing lib requires headers in lower case for now
        $headers = $this->headers ?? FromGlobals::getHeaders();
        return array_change_key_case($headers, CASE_LOWER);
    }

    /**
     * @param EventInterface $event
     * @return array
     */
    private function getEventHandlers(EventInterface $event): array
    {
        $interfaces = class_implements($event);
        $interfaces = array_unique($interfaces);
        $handlers = array_map(fn($interface) => $this->handlers[$interface] ?? [], $interfaces);
        return Arr::flatten($handlers);
    }

    /**
     * s     * @return EventInterface
     * @throws InvalidArgumentException
     * @throws ValidationException
     * @throws WebhookHandlerInvalidArgumentException
     */
    private function getEventEntity(): EventInterface
    {
        $data = $this->getDecodedBody();

        $headers = $this->getHeaders();
        $data['body'] = $this->getBody();
        $data['headers'] = $headers;
        $data['timestamp'] = $headers[strtolower(CustomHeaders::WEBHOOK_TIMESTAMP)] ?? '';
        $data['signature'] = $headers[strtolower(CustomHeaders::SIGNATURE)];

        try {
            return $this->entityFactory->make(EventInterface::class, $data);
        } catch (Exception $e) {
            // If we do not recognise the data structure as valid for any of the existing entities,
            // We create the base event entity which will be passed to the default handler.
            if ($e instanceof ValidationException || $e instanceof InvalidArgumentException) {
                if ($e instanceof ValidationException) {
                    var_dump('exception thrown', $e->getErrors());
                } else {
                    var_dump('exception thrown', $e);
                }
                return $this->entityFactory->makeConcrete(Event::class)->fill($data);
            }
            throw $e;
        }
    }

    /**
     * @throws WebhookHandlerInvalidArgumentException
     */
    private function verify(): void
    {
        // The verification process requires a path without the trailing slash
        $path = rtrim($this->path ?? FromGlobals::getPath(), '/');

        $headers = $this->getHeaders();

        // We need the signature header to validate against
        $signatureHeader = strtolower(CustomHeaders::SIGNATURE);
        if (empty($headers[$signatureHeader])) {
            throw new WebhookHandlerInvalidArgumentException("$signatureHeader header not provided.");
        }

        $this->verifier
            ->method('POST')
            ->path($path)
            ->body($this->getBody())
            ->headers($headers)
            ->verify($headers[$signatureHeader]);
    }
}
