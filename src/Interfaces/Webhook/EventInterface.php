<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Webhook;

use DateTimeInterface;

interface EventInterface
{
    /**
     * Get the time that the webhook was sent to you
     * @return DateTimeInterface
     */
    public function getTimestamp(): DateTimeInterface;

    /**
     * Get the JSON web signature with a detached payload of the form {HEADER}..{SIGNATURE}
     * @return string
     */
    public function getSignature(): string;

    /**
     * Get the event type
     * @return string
     */
    public function getType(): string;

    /**
     * Get the event id
     * @return string
     */
    public function getEventId(): string;

    /**
     * Get the event version
     * @return string
     */
    public function getEventVersion(): string;
}
