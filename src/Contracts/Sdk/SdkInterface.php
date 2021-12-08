<?php

namespace TrueLayer\Contracts\Sdk;

use TrueLayer\Contracts\Api\ApiClientInterface;
use TrueLayer\Contracts\Beneficiary\BeneficiaryBuilderInterface;
use TrueLayer\Contracts\Hpp\HppHelperInterface;
use TrueLayer\Contracts\Payment\PaymentRequestInterface;
use TrueLayer\Contracts\Payment\PaymentRetrievedInterface;
use TrueLayer\Contracts\UserInterface;

interface SdkInterface
{
    /**
     * @return ApiClientInterface
     */
    public function getApiClient(): ApiClientInterface;

    /**
     * @param array $data
     *
     * @return UserInterface
     */
    public function user(array $data = []): UserInterface;

    /**
     * @return BeneficiaryBuilderInterface
     */
    public function beneficiary(): BeneficiaryBuilderInterface;

    /**
     * @param array $data
     *
     * @return PaymentRequestInterface
     */
    public function payment(array $data = []): PaymentRequestInterface;

    /**
     * @param string $id
     *
     * @throws \TrueLayer\Exceptions\ApiRequestJsonSerializationException
     * @throws \TrueLayer\Exceptions\ApiRequestValidationException
     * @throws \TrueLayer\Exceptions\ApiResponseUnsuccessfulException
     * @throws \TrueLayer\Exceptions\ApiResponseValidationException
     *
     * @return PaymentRetrievedInterface
     */
    public function getPaymentDetails(string $id): PaymentRetrievedInterface;

    /**
     * @return HppHelperInterface
     */
    public function hostedPaymentsPage(): HppHelperInterface;
}
