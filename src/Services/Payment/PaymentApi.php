<?php

declare(strict_types=1);

namespace TrueLayer\Services\Payment;

use TrueLayer\Constants\Endpoints;
use TrueLayer\Contracts\Beneficiary\BeneficiaryInterface;
use TrueLayer\Contracts\Payment\PaymentCreatedInterface;
use TrueLayer\Contracts\Payment\PaymentRequestInterface;
use TrueLayer\Contracts\Payment\PaymentRetrievedInterface;
use TrueLayer\Contracts\UserInterface;
use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Exceptions\ValidationException;
use TrueLayer\Services\Beneficiary\BeneficiaryBuilder;
use TrueLayer\Services\Payment\PaymentCreated;
use TrueLayer\Services\User;
use TrueLayer\Traits\WithSdk;
use TrueLayer\Validation\BeneficiaryRules;
use TrueLayer\Validation\PaymentRules;

final class PaymentApi
{
    use WithSdk;

    /**
     * @param PaymentRequestInterface $paymentRequest
     * @return PaymentCreatedInterface
     * @throws ApiRequestJsonSerializationException
     * @throws ValidationException
     */
    public function create(PaymentRequestInterface $paymentRequest): PaymentCreatedInterface
    {
        $response = $this->getSdk()->getApiClient()->request()
            ->uri(Endpoints::PAYMENTS)
            ->payload($paymentRequest->toArray())
            ->post();

        return PaymentCreated::make($this->getSdk())->fill($response);
    }

    /**
     * @param string $id
     * @return PaymentRetrievedInterface
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     * @throws ValidationException
     * @throws InvalidArgumentException
     */
    public function retrieve(string $id): PaymentRetrievedInterface
    {
        $sdk = $this->getSdk();

        $response = $sdk->getApiClient()->request()
            ->uri(Endpoints::PAYMENTS . '/' . $id)
            ->get();

        $data = array_merge($response, [
            'beneficiary' => $sdk->beneficiary()->fill($response['beneficiary'] ?? []),
            'user' => $sdk->user()->fill($response['user'] ?? [])
        ]);

        return PaymentRetrieved::make($sdk)->fill($data);
    }
}
