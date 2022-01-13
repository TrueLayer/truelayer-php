<?php

declare(strict_types=1);

namespace TrueLayer\Contracts\Sdk;

use Illuminate\Contracts\Validation\Factory as ValidatorFactory;
use TrueLayer\Contracts\ApiClient\ApiClientInterface;
use TrueLayer\Contracts\Beneficiary\BeneficiaryBuilderInterface;
use TrueLayer\Contracts\HppInterface;
use TrueLayer\Contracts\Payment\PaymentMethodInterface;
use TrueLayer\Contracts\Payment\PaymentRequestInterface;
use TrueLayer\Contracts\Payment\PaymentRetrievedInterface;
use TrueLayer\Contracts\Provider\ProviderFilterInterface;
use TrueLayer\Contracts\UserInterface;
use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\SignerException;
use TrueLayer\Exceptions\ValidationException;

interface SdkInterface
{
    /**
     * @return ApiClientInterface
     */
    public function getApiClient(): ApiClientInterface;

    /**
     * @return ValidatorFactory
     */
    public function getValidatorFactory(): ValidatorFactory;

    /**
     * @return UserInterface
     */
    public function user(): UserInterface;

    /**
     * @return BeneficiaryBuilderInterface
     */
    public function beneficiary(): BeneficiaryBuilderInterface;

    /**
     * @return PaymentMethodInterface
     */
    public function paymentMethod(): PaymentMethodInterface;

    /**
     * @return ProviderFilterInterface
     */
    public function providerFilter(): ProviderFilterInterface;

    /**
     * @return PaymentRequestInterface
     */
    public function payment(): PaymentRequestInterface;

    /**
     * @param string $id
     *
     *@throws ApiResponseUnsuccessfulException
     * @throws SignerException
     * @throws ValidationException
     * @throws ApiRequestJsonSerializationException
     *
     * @return PaymentRetrievedInterface
     */
    public function getPaymentDetails(string $id): PaymentRetrievedInterface;

    /**
     * @return HppInterface
     */
    public function hostedPaymentsPage(): HppInterface;
}
