<?php

declare(strict_types=1);

namespace TrueLayer\Builders;

use TrueLayer\Contracts\Api\PaymentCreateInterface;
use TrueLayer\Contracts\Builders\PaymentRequestBuilderInterface;
use TrueLayer\Contracts\Models\PaymentCreatedInterface;
use TrueLayer\Models\Payment;

class PaymentRequestBuilder extends Payment implements PaymentRequestBuilderInterface
{
    /**
     * @var PaymentCreateInterface
     */
    private PaymentCreateInterface $apiHandler;

    /**
     * @param PaymentCreateInterface $apiHandler
     */
    public function __construct(PaymentCreateInterface $apiHandler)
    {
        $this->apiHandler = $apiHandler;
    }

    /**
     * @return PaymentCreatedInterface
     * @throws \TrueLayer\Exceptions\ApiRequestJsonSerializationException
     * @throws \TrueLayer\Exceptions\ApiRequestValidationException
     * @throws \TrueLayer\Exceptions\ApiResponseUnsuccessfulException
     * @throws \TrueLayer\Exceptions\ApiResponseValidationException
     * @throws \TrueLayer\Exceptions\AuthTokenRetrievalFailure
     */
    public function create(): PaymentCreatedInterface
    {
        return $this->apiHandler->send($this);
    }
}
