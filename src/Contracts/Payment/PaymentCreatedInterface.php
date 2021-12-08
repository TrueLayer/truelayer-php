<?php

declare(strict_types=1);

namespace TrueLayer\Contracts\Payment;

use TrueLayer\Contracts\ArrayableInterface;
use TrueLayer\Contracts\ArrayFillableInterface;
use TrueLayer\Contracts\Hpp\HppHelperInterface;

interface PaymentCreatedInterface extends ArrayableInterface, ArrayFillableInterface
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
     * @return HppHelperInterface
     */
    public function hostedPaymentsPage(): HppHelperInterface;

    /**
     * @return PaymentRetrievedInterface
     * @throws \TrueLayer\Exceptions\ApiRequestJsonSerializationException
     * @throws \TrueLayer\Exceptions\ApiRequestValidationException
     * @throws \TrueLayer\Exceptions\ApiResponseUnsuccessfulException
     * @throws \TrueLayer\Exceptions\ApiResponseValidationException
     */
    public function getDetails(): PaymentRetrievedInterface;
}
