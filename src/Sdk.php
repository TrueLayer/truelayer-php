<?php

declare(strict_types=1);

namespace TrueLayer;

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
     * @var HppHelperFactoryInterface
     */
    private HppHelperFactoryInterface $hppHelperFactory;

    /**
     * @param ApiClientInterface        $apiClient
     * @param HppHelperFactoryInterface $hppHelperFactory
     */
    public function __construct(ApiClientInterface $apiClient, HppHelperFactoryInterface $hppHelperFactory)
    {
        $this->apiClient = $apiClient;
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
     * @param array $data
     *
     * @return UserInterface
     */
    public function user(array $data = []): UserInterface
    {
        return User::make($this)->fill($data);
    }

    /**
     * @return BeneficiaryBuilderInterface
     */
    public function beneficiary(): BeneficiaryBuilderInterface
    {
        return BeneficiaryBuilder::make($this);
    }

    /**
     * @param array $data
     *
     * @return PaymentRequestInterface
     */
    public function payment(array $data = []): PaymentRequestInterface
    {
        return PaymentRequest::make($this)->fill($data);
    }

    /**
     * @param string $id
     *
     * @throws Exceptions\ApiRequestJsonSerializationException
     * @throws Exceptions\ApiRequestValidationException
     * @throws Exceptions\ApiResponseUnsuccessfulException
     * @throws Exceptions\ApiResponseValidationException
     * @throws InvalidArgumentException
     *
     * @return PaymentRetrievedInterface
     */
    public function getPaymentDetails(string $id): PaymentRetrievedInterface
    {
        $data = PaymentRetrieve::make($this)->execute($id);

        return PaymentRetrieved::make($this)->fill($data);
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
