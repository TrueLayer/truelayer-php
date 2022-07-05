<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payment;

use DateTimeInterface;
use TrueLayer\Interfaces\ArrayableInterface;

interface RefundRetrievedInterface extends ArrayableInterface
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
     * @return string
     */
    public function getReference(): string;

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
