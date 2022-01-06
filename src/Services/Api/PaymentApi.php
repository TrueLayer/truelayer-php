<?php

declare(strict_types=1);

namespace TrueLayer\Services\Api;

use TrueLayer\Constants\Endpoints;
use TrueLayer\Contracts\Payment\PaymentCreatedInterface;
use TrueLayer\Contracts\Payment\PaymentRequestInterface;
use TrueLayer\Contracts\Payment\PaymentRetrievedInterface;
use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Exceptions\ValidationException;
use TrueLayer\Models\Payment\PaymentCreated;
use TrueLayer\Models\Payment\PaymentRetrieved;
use TrueLayer\Traits\WithSdk;

final class PaymentApi
{
    use WithSdk;

    /**
     * @param PaymentRequestInterface $paymentRequest
     *
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     * @throws InvalidArgumentException
     * @throws ValidationException
     *
     * @return PaymentCreatedInterface
     */
    public function create(PaymentRequestInterface $paymentRequest): PaymentCreatedInterface
    {
        $response = (array) $this->getSdk()->getApiClient()->request()
            ->uri(Endpoints::PAYMENTS)
            ->payload($paymentRequest->validate()->toArray())
            ->post();

        return PaymentCreated::make($this->getSdk())->fill($response);
    }

    /**
     * @param string $id
     *
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     * @throws ValidationException
     * @throws InvalidArgumentException
     *
     * @return PaymentRetrievedInterface
     */
    public function retrieve(string $id): PaymentRetrievedInterface
    {
        $sdk = $this->getSdk();

        $response = (array) $sdk->getApiClient()->request()
            ->uri(Endpoints::PAYMENTS . '/' . $id)
            ->get();

        return PaymentRetrieved::make($sdk)->fill($response);
    }
}
