<?php

declare(strict_types=1);

namespace TrueLayer\Contracts\Models;

use TrueLayer\Contracts\ArrayableInterface;
use TrueLayer\Contracts\ArrayFactoryInterface;

interface PaymentCreatedInterface extends ArrayableInterface, ArrayFactoryInterface
{
    /**
     * @return string|null
     */
    public function getPaymentId(): ?string;

    /**
     * @return string
     */
    public function getResourceToken(): string;

    /**
     * @return string
     */
    public function getUserId(): string;
}
