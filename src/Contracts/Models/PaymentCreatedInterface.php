<?php

declare(strict_types=1);

namespace TrueLayer\Contracts\Models;

interface PaymentCreatedInterface
{
    /**
     * @return string
     */
    public function getPaymentId(): string;

    /**
     * @return string
     */
    public function getResourceToken(): string;

    /**
     * @return string
     */
    public function getUserId(): string;

    /**
     * @return array
     */
    public function toArray(): array;
}
