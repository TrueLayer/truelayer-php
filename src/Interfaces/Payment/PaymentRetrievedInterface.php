<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payment;

use TrueLayer\Interfaces\ArrayableInterface;
use TrueLayer\Interfaces\PaymentMethod\PaymentMethodInterface;

interface PaymentRetrievedInterface extends ArrayableInterface
{
    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @return int
     */
    public function getAmountInMinor(): int;

    /**
     * @return string
     */
    public function getCurrency(): string;

    /**
     * @return array<string, string>
     */
    public function getMetadata(): array;

    /**
     * @return PaymentMethodInterface
     */
    public function getPaymentMethod(): PaymentMethodInterface;

    /**
     * @return string
     */
    public function getUserId(): string;

    /**
     * @return \DateTimeInterface
     */
    public function getCreatedAt(): \DateTimeInterface;

    /**
     * @return string
     */
    public function getStatus(): string;

    /**
     * @return bool
     */
    public function isAuthorizationRequired(): bool;

    /**
     * @return bool
     */
    public function isAuthorizing(): bool;

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

    /**
     * @return bool
     */
    public function isAttemptFailed(): bool;

    /**
     * @return bool
     */
    public function isSettled(): bool;
}
