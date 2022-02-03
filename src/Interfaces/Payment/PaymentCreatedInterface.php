<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payment;

use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Interfaces\ArrayableInterface;
use TrueLayer\Interfaces\HppInterface;

interface PaymentCreatedInterface extends ArrayableInterface
{
    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @return string
     */
    public function getResourceToken(): string;

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
