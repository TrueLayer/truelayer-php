<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Webhook;

interface PayoutExecutedEventInterface extends PayoutEventInterface
{
    /**
     * Get the payment execution date.
     *
     * @return \DateTimeInterface
     */
    public function getExecutedAt(): \DateTimeInterface;

    /**
     * Get the unique identifier for the scheme.
     *
     * @return string|null
     */
    public function getSchemeId(): ?string;
}
