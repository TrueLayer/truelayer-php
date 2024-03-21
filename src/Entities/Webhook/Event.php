<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Webhook;

use TrueLayer\Entities\Entity;
use TrueLayer\Interfaces\Webhook\EventInterface;

class Event extends Entity implements EventInterface
{
    /**
     * @var \DateTimeInterface
     */
    protected \DateTimeInterface $timestamp;

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
     * @var mixed[]
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
    protected array $casts = [
        'timestamp' => \DateTimeInterface::class,
    ];

    /**
     * @return \DateTimeInterface
     */
    public function getTimestamp(): \DateTimeInterface
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
     * @return string
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
     * @return mixed[]
     */
    public function getBody(): array
    {
        $decoded = \json_decode($this->body, true);

        return \is_array($decoded)
            ? $decoded
            : [];
    }
}
