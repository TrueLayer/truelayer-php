<?php

declare(strict_types=1);

namespace TrueLayer;

use Illuminate\Contracts\Validation\Factory as ValidatorFactory;
use TrueLayer\Contracts\Api\ApiClientInterface;
use TrueLayer\Contracts\Beneficiary\BeneficiaryBuilderInterface;
use TrueLayer\Contracts\Hpp\HppHelperFactoryInterface;
use TrueLayer\Contracts\Hpp\HppHelperInterface;
use TrueLayer\Contracts\Payment\PaymentRequestInterface;
use TrueLayer\Contracts\Payment\PaymentRetrievedInterface;
use TrueLayer\Contracts\Sdk\SdkConfigInterface;
use TrueLayer\Contracts\Sdk\SdkInterface;
use TrueLayer\Contracts\UserInterface;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Services\Beneficiary\BeneficiaryBuilder;
use TrueLayer\Services\Payment\Api\PaymentRetrieve;
use TrueLayer\Services\Payment\PaymentApi;
use TrueLayer\Services\Payment\PaymentRequest;
use TrueLayer\Services\Payment\PaymentRetrieved;
use TrueLayer\Services\Sdk\SdkConfig;
use TrueLayer\Services\Sdk\SdkFactory;
use TrueLayer\Services\User;

final class Sdk implements SdkInterface
{
    /**
     * @var ApiClientInterface
     */
    private ApiClientInterface $apiClient;

    /**
     * @var ValidatorFactory
     */
    private ValidatorFactory $validatorFactory;

    /**
     * @var HppHelperFactoryInterface
     */
    private HppHelperFactoryInterface $hppHelperFactory;

    /**
     * @param ApiClientInterface $apiClient
     * @param ValidatorFactory $validatorFactory
     * @param HppHelperFactoryInterface $hppHelperFactory
     */
    public function __construct(ApiClientInterface $apiClient, ValidatorFactory $validatorFactory, HppHelperFactoryInterface $hppHelperFactory)
    {
        $this->apiClient = $apiClient;
        $this->validatorFactory = $validatorFactory;
        $this->hppHelperFactory = $hppHelperFactory;
    }

    /**
     * @return ApiClientInterface
     */
    public function getApiClient(): ApiClientInterface
    {
        return $this->apiClient;
    }

    /**
     * @return ValidatorFactory
     */
    public function getValidatorFactory(): ValidatorFactory
    {
        return $this->validatorFactory;
    }

    /**
     * @return UserInterface
     */
    public function user(): UserInterface
    {
        return User::make($this);
    }

    /**
     * @return BeneficiaryBuilderInterface
     */
    public function beneficiary(): BeneficiaryBuilderInterface
    {
        return BeneficiaryBuilder::make($this);
    }

    /**
     * @return PaymentRequestInterface
     */
    public function payment(): PaymentRequestInterface
    {
        return PaymentRequest::make($this);
    }

    /**
     * @param string $id
     *
     * @return PaymentRetrievedInterface
     *
     * @throws Exceptions\ApiRequestJsonSerializationException
     * @throws Exceptions\ApiResponseUnsuccessfulException
     * @throws Exceptions\ValidationException
     */
    public function getPaymentDetails(string $id): PaymentRetrievedInterface
    {
        return PaymentApi::make($this)->retrieve($id);
    }

    /**
     * @return HppHelperInterface
     */
    public function hostedPaymentsPage(): HppHelperInterface
    {
        return $this->hppHelperFactory->make();
    }

    /**
     * @return SdkConfigInterface
     */
    public static function configure(): SdkConfigInterface
    {
        return new SdkConfig(new SdkFactory());
    }
}
