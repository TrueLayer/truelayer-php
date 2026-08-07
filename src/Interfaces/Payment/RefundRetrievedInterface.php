<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payment;

use TrueLayer\Interfaces\ArrayableInterface;

/**
 * Fields and helpers shared by a refund resource regardless of its current status.
 */
interface RefundRetrievedInterface extends ArrayableInterface
{
    /**
     * The unique ID for the refund.
     *
     * @return string
     */
    public function getId(): string;

    /**
     * A 'cent' value representing the refund amount, e.g. 100 = 1 GBP or 100 = 1 EUR.
     *
     * @return int
     */
    public function getAmountInMinor(): int;

    /**
     * The three-letter ISO 4217 currency code the refund was made in.
     *
     * @return string
     */
    public function getCurrency(): string;

    /**
     * The reference for the refund.
     *
     * @return string
     */
    public function getReference(): string;

    /**
     * Custom key-value data attached to the refund.
     *
     * @return array<string, string>
     */
    public function getMetadata(): array;

    /**
     * The date and time the refund was created at.
     *
     * @return \DateTimeInterface
     */
    public function getCreatedAt(): \DateTimeInterface;

    /**
     * The current status of the refund.
     *
     * @return string
     */
    public function getStatus(): string;

    /**
     * @return bool
     */
    public function isPending(): bool;

    /**
     * @return bool
     */
    public function isAuthorized(): bool;

    /**
     * @return bool
     */
    public function isExecuted(): bool;

    /**
     * @return bool
     */
    public function isFailed(): bool;
}
