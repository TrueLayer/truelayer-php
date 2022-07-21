<?php

declare(strict_types=1);

namespace TrueLayer\Services\Webhooks;

use Exception;
use ReflectionException;
use TrueLayer\Constants\CustomHeaders;
use TrueLayer\Entities\Webhook\Event;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Exceptions\ValidationException;
use TrueLayer\Exceptions\WebhookHandlerException;
use TrueLayer\Exceptions\WebhookHandlerInvalidArgumentException;
use TrueLayer\Exceptions\WebhookVerificationFailedException;
use TrueLayer\Interfaces\Factories\EntityFactoryInterface;
use TrueLayer\Interfaces\Webhook\EventInterface;
use TrueLayer\Interfaces\Webhook\WebhookHandlerManagerInterface;
use TrueLayer\Interfaces\Webhook\WebhookInterface;
use TrueLayer\Interfaces\Webhook\WebhookVerifierInterface;
use TrueLayer\Services\Util\FromGlobals;

class Webhook implements WebhookInterface
{
    /**
     * @var WebhookVerifierInterface
     */
    private WebhookVerifierInterface $verifier;

    /**
     * @var EntityFactoryInterface
     */
    private EntityFactoryInterface $entityFactory;

    /**
     * @var WebhookHandlerManagerInterface
     */
    private WebhookHandlerManagerInterface $handlerManager;

    /**
     * @var string
     */
    private string $path;

    /**
     * @var string
     */
    private string $body;

    /**
     * @var array<string, string>
     */
    private array $headers;

    /**
     * @param WebhookVerifierInterface       $verifier
     * @param EntityFactoryInterface         $entityFactory
     * @param WebhookHandlerManagerInterface $handlerManager
     */
    public function __construct(WebhookVerifierInterface $verifier, EntityFactoryInterface $entityFactory, WebhookHandlerManagerInterface $handlerManager)
    {
        $this->verifier = $verifier;
        $this->entityFactory = $entityFactory;
        $this->handlerManager = $handlerManager;
    }

    /**
     * @param callable|class-string $handler
     *
     * @throws ReflectionException
     * @throws WebhookHandlerInvalidArgumentException
     * @throws WebhookHandlerException
     *
     * @return WebhookInterface
     */
    public function handler($handler): WebhookInterface
    {
        $this->handlerManager->add($handler);

        return $this;
    }

    /**
     * @param callable|class-string ...$handlers
     *
     * @throws ReflectionException
     * @throws WebhookHandlerInvalidArgumentException
     * @throws WebhookHandlerException
     *
     * @return WebhookInterface
     */
    public function handlers(...$handlers): WebhookInterface
    {
        $this->handlerManager->addMany(...$handlers);

        return $this;
    }

    /**
     * @param string $path
     *
     * @return WebhookInterface
     */
    public function path(string $path): WebhookInterface
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @param string $body
     *
     * @return WebhookInterface
     */
    public function body(string $body): WebhookInterface
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @param array<string, string> $headers
     *
     * @return WebhookInterface
     */
    public function headers(array $headers): WebhookInterface
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * @throws InvalidArgumentException
     * @throws ValidationException
     * @throws WebhookHandlerInvalidArgumentException
     * @throws WebhookVerificationFailedException
     */
    public function execute(): void
    {
        $this->verifier->verify(
            $this->path ?? FromGlobals::getPath(),
            $this->getHeaders(),
            $this->getBody()
        );

        $this->handlerManager->execute(
            $this->getEventEntity()
        );
    }

    /**
     * @return string
     */
    private function getBody(): string
    {
        return $this->body ?? FromGlobals::getBody();
    }

    /**
     * @throws WebhookHandlerInvalidArgumentException
     *
     * @return mixed[]
     */
    private function getDecodedBody(): array
    {
        $payload = \json_decode($this->getBody(), true);

        if (!\is_array($payload) || empty($payload) || \json_last_error() !== JSON_ERROR_NONE) {
            throw new WebhookHandlerInvalidArgumentException('Body is empty or not in json format');
        }

        return $payload;
    }

    /**
     * @return array<string, string>
     */
    private function getHeaders(): array
    {
        // The signing lib requires headers in lower case for now
        $headers = $this->headers ?? FromGlobals::getHeaders();

        return \array_change_key_case($headers, CASE_LOWER);
    }

    /**
     * @throws InvalidArgumentException
     * @throws ValidationException
     * @throws WebhookHandlerInvalidArgumentException
     *
     * s     * @return EventInterface
     */
    private function getEventEntity(): EventInterface
    {
        $data = $this->getDecodedBody();

        $headers = $this->getHeaders();
        $data['body'] = $this->getBody();
        $data['timestamp'] = $headers[\strtolower(CustomHeaders::WEBHOOK_TIMESTAMP)] ?? '';
        $data['signature'] = $headers[\strtolower(CustomHeaders::SIGNATURE)];

        try {
            return $this->entityFactory->make(EventInterface::class, $data);
        } catch (Exception $e) {
            // If we do not recognise the data structure as valid for any of the existing entities,
            // We create the base event entity which will be passed to the default handler.
            if ($e instanceof ValidationException || $e instanceof InvalidArgumentException) {
                return $this->entityFactory->makeConcrete(Event::class)->fill($data);
            }
            throw $e;
        }
    }
}
