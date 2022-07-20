<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Webhook;

use DateTimeInterface;
use TrueLayer\Entities\Entity;
use TrueLayer\Interfaces\Webhook\EventInterface;

class Event extends Entity implements EventInterface
{
    /**
     * @var DateTimeInterface
     */
    protected DateTimeInterface $timestamp;

    /**
     * @var string
     */
    protected string $signature;

    /**
     * @var string
     */
    protected string $type;

    /**
     * @var string
     */
    protected string $eventId;

    /**
     * @var int
     */
    protected int $eventVersion;

    /**
     * @var string
     */
    protected string $body;

    /**
     * @var array
     */
    protected array $headers;

    /**
     * @return string[]
     */
    protected array $arrayFields = [
        'type',
        'event_id',
        'event_version',
    ];

    /**
     * @var string[]
     */
    protected array $rules = [
        'timestamp' => 'required|date',
        'signature' => 'required|string',
        'type' => 'required|string',
        'event_id' => 'required|string',
        'event_version' => 'required|int',
        'body' => 'required|string',
        'headers' => 'required|array'
    ];

    /**
     * @var string[]
     */
    protected array $casts = [
        'timestamp' => DateTimeInterface::class,
    ];

    /**
     * @return DateTimeInterface
     */
    public function getTimestamp(): DateTimeInterface
    {
        return $this->timestamp;
    }

    /**
     * @return string
     */
    public function getSignature(): string
    {
        return $this->signature;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return int
     */
    public function getEventId(): string
    {
        return $this->eventId;
    }

    /**
     * @return int
     */
    public function getEventVersion(): int
    {
        return $this->eventVersion;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @return mixed[]
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }
}
