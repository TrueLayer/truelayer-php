<?php

declare(strict_types=1);

namespace TrueLayer\Contracts\Payment;

use TrueLayer\Contracts\ArrayableInterface;
use TrueLayer\Contracts\HppInterface;
use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;

interface PaymentCreatedInterface extends ArrayableInterface
{
    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @return string
     */
    public function getPaymentToken(): string;

    /**
     * @return string
     */
    public function getUserId(): string;

    /**
     * @return HppInterface
     */
    public function hostedPaymentsPage(): HppInterface;

    /**
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     *
     * @return PaymentRetrievedInterface
     */
    public function getDetails(): PaymentRetrievedInterface;
}
