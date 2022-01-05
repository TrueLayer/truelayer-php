<?php

declare(strict_types=1);

namespace TrueLayer\Contracts\Payment;

use TrueLayer\Contracts\ArrayableInterface;
use TrueLayer\Contracts\Hpp\HppHelperInterface;

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
     * @return HppHelperInterface
     */
    public function hostedPaymentsPage(): HppHelperInterface;

    /**
     * @throws \TrueLayer\Exceptions\ApiRequestJsonSerializationException
     * @throws \TrueLayer\Exceptions\ApiResponseUnsuccessfulException
     *
     * @return PaymentRetrievedInterface
     */
    public function getDetails(): PaymentRetrievedInterface;
}
