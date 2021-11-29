<?php

declare(strict_types=1);

namespace TrueLayer\Contracts\Api;

use TrueLayer\Contracts\Models\PaymentInterface;
use TrueLayer\Contracts\Models\PaymentCreatedInterface;

interface PaymentCreateInterface
{
    /**
     * @param PaymentInterface $model
     * @return PaymentCreatedInterface
     * @throws \TrueLayer\Exceptions\ApiRequestJsonSerializationException
     * @throws \TrueLayer\Exceptions\ApiRequestValidationException
     * @throws \TrueLayer\Exceptions\ApiResponseUnsuccessfulException
     * @throws \TrueLayer\Exceptions\ApiResponseValidationException
     * @throws \TrueLayer\Exceptions\AuthTokenRetrievalFailure
     */
    public function send(PaymentInterface $model): PaymentCreatedInterface;
}
