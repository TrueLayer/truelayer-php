<?php

declare(strict_types=1);

namespace TrueLayer\Models;

use TrueLayer\Contracts\Models\PaymentCreatedInterface;

class PaymentCreated implements PaymentCreatedInterface
{
    /**
     * @var array
     */
    private array $data;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getPaymentId(): string
    {
        return $this->data['id'];
    }

    /**
     * @return string
     */
    public function getResourceToken(): string
    {
        return $this->data['resource_token'];
    }

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->data['user']['id'];
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->data;
    }

    /**
     * @param array $data
     * @return PaymentCreatedInterface
     */
    public static function fromArray(array $data = []): PaymentCreatedInterface
    {
        return new static($data);
    }
}
