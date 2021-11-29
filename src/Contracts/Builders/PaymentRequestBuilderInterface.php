<?php

namespace TrueLayer\Contracts\Builders;

use TrueLayer\Contracts\Models\PaymentCreatedInterface;
use TrueLayer\Contracts\Models\PaymentInterface;

interface PaymentRequestBuilderInterface extends PaymentInterface
{
    /**
     * @return PaymentCreatedInterface
     * @throws \TrueLayer\Exceptions\ApiRequestJsonSerializationException
     * @throws \TrueLayer\Exceptions\ApiRequestValidationException
     * @throws \TrueLayer\Exceptions\ApiResponseUnsuccessfulException
     * @throws \TrueLayer\Exceptions\ApiResponseValidationException
     * @throws \TrueLayer\Exceptions\AuthTokenRetrievalFailure
     */
    public function create(): PaymentCreatedInterface;
}
