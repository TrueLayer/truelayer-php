<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payment;

use TrueLayer\Interfaces\ArrayableInterface;
use TrueLayer\Interfaces\PaymentMethod\PaymentMethodInterface;

/**
 * Fields and helpers shared by a payment resource regardless of its current status.
 */
interface PaymentRetrievedInterface extends ArrayableInterface
{
    /**
     * The unique ID for the payment.
     *
     * @return string
     */
    public function getId(): string;

    /**
     * A 'cent' value representing the amount, e.g. 100 = 1 GBP or 100 = 1 EUR.
     *
     * @return int
     */
    public function getAmountInMinor(): int;

    /**
     * The three-letter ISO 4217 currency code the payment was made in.
     *
     * @return string
     */
    public function getCurrency(): string;

    /**
     * Custom key-value data attached to the payment.
     *
     * @return array<string, string>
     */
    public function getMetadata(): array;

    /**
     * @return PaymentMethodInterface
     */
    public function getPaymentMethod(): PaymentMethodInterface;

    /**
     * The unique ID of the user who made the payment.
     *
     * @return string
     */
    public function getUserId(): string;

    /**
     * The date and time the payment was created at.
     *
     * @return \DateTimeInterface
     */
    public function getCreatedAt(): \DateTimeInterface;

    /**
     * The current status of the payment.
     *
     * @return string
     */
    public function getStatus(): string;

    /**
     * The list of failed payment attempts, if any. Only populated when payment retries are enabled.
     *
     * @return PaymentFailedAttemptInterface[]
     */
    public function getFailedAttempts(): array;

    /**
     * @return bool
     */
    public function isAuthorizationRequired(): bool;

    /**
     * @return bool
     */
    public function isAuthorizing(): bool;

    /**
     * Will also return false when the payment has progressed to executed, failed or settled states.
     *
     * @return bool
     */
    public function isAuthorized(): bool;

    /**
     * Will also return false when the payment has progressed to failed or settled states.
     *
     * @return bool
     */
    public function isExecuted(): bool;

    /**
     * @return bool
     */
    public function isFailed(): bool;

    /**
     * Only true if payment retries are enabled and this attempt failed.
     *
     * @return bool
     */
    public function isAttemptFailed(): bool;

    /**
     * @return bool
     */
    public function isSettled(): bool;

    /**
     * Cancels the payment, as long as it hasn't been authorized yet, and returns a fresh version of the
     * retrieved payment.
     *
     * @return PaymentRetrievedInterface
     */
    public function cancel(): PaymentRetrievedInterface;
}
