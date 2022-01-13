<?php

declare(strict_types=1);

namespace TrueLayer\Services\Api;

use TrueLayer\Constants\Endpoints;
use TrueLayer\Constants\PaymentStatus;
use TrueLayer\Contracts\Payment\PaymentCreatedInterface;
use TrueLayer\Contracts\Payment\PaymentRequestInterface;
use TrueLayer\Contracts\Payment\PaymentRetrievedInterface;
use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Exceptions\SignerException;
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
     * @throws ApiResponseUnsuccessfulException
     * @throws InvalidArgumentException
     * @throws SignerException
     * @throws ValidationException
     * @throws ApiRequestJsonSerializationException
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
     *@throws ApiResponseUnsuccessfulException
     * @throws InvalidArgumentException
     * @throws ValidationException
     * @throws SignerException
     * @throws ApiRequestJsonSerializationException
     *
     * @return PaymentRetrievedInterface
     */
    public function retrieve(string $id): PaymentRetrievedInterface
    {
        $sdk = $this->getSdk();

        $response = (array) $sdk->getApiClient()->request()
            ->uri(Endpoints::PAYMENTS . '/' . $id)
            ->get();

        $states = [
            PaymentStatus::AUTHORIZATION_REQUIRED => PaymentRetrieved\PaymentAuthorizationRequired::class,
            PaymentStatus::AUTHORIZING => PaymentRetrieved\PaymentAuthorizing::class,
            PaymentStatus::AUTHORIZED => PaymentRetrieved\PaymentAuthorized::class,
            PaymentStatus::EXECUTED => PaymentRetrieved\PaymentExecuted::class,
            PaymentStatus::SETTLED => PaymentRetrieved\PaymentSettled::class,
            PaymentStatus::FAILED => PaymentRetrieved\PaymentFailed::class,
        ];

        $model = isset($response['status']) && isset($states[$response['status']])
            ? $states[$response['status']]
            : PaymentRetrieved::class;

        return $model::make($sdk)->fill($response);
    }
}
