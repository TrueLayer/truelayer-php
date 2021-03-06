<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payment;

use DateTimeInterface;
use TrueLayer\Interfaces\ArrayableInterface;
use TrueLayer\Interfaces\PaymentMethod\PaymentMethodInterface;
use TrueLayer\Interfaces\UserInterface;

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
     * @return PaymentMethodInterface
     */
    public function getPaymentMethod(): PaymentMethodInterface;

    /**
     * @return UserInterface|null
     */
    public function getUser(): ?UserInterface;

    /**
     * @return DateTimeInterface
     */
    public function getCreatedAt(): DateTimeInterface;

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
    public function isSettled(): bool;
}
