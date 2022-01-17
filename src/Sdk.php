<?php

declare(strict_types=1);

namespace TrueLayer;

use Illuminate\Contracts\Validation\Factory as ValidatorFactory;
use TrueLayer\Constants\Endpoints;
use TrueLayer\Contracts\ApiClient\ApiClientInterface;
use TrueLayer\Contracts\Beneficiary\BeneficiaryBuilderInterface;
use TrueLayer\Contracts\HppInterface;
use TrueLayer\Contracts\Payment\PaymentMethodInterface;
use TrueLayer\Contracts\Payment\PaymentRequestInterface;
use TrueLayer\Contracts\Payment\PaymentRetrievedInterface;
use TrueLayer\Contracts\Provider\ProviderFilterInterface;
use TrueLayer\Contracts\Sdk\SdkConfigInterface;
use TrueLayer\Contracts\Sdk\SdkInterface;
use TrueLayer\Contracts\UserInterface;
use TrueLayer\Models\Beneficiary\BeneficiaryBuilder;
use TrueLayer\Models\Hpp;
use TrueLayer\Models\Payment\PaymentMethod;
use TrueLayer\Models\Payment\PaymentRequest;
use TrueLayer\Models\Provider\ProviderFilter;
use TrueLayer\Models\User;
use TrueLayer\Services\Api\PaymentApi;
use TrueLayer\Services\Sdk\SdkConfig;
use TrueLayer\Services\Sdk\SdkFactory;

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
     * @var SdkConfigInterface
     */
    private SdkConfigInterface $config;

    /**
     * @param ApiClientInterface $apiClient
     * @param ValidatorFactory   $validatorFactory
     * @param SdkConfigInterface $config
     */
    public function __construct(
        ApiClientInterface $apiClient,
        ValidatorFactory $validatorFactory,
        SdkConfigInterface $config
    ) {
        $this->apiClient = $apiClient;
        $this->validatorFactory = $validatorFactory;
        $this->config = $config;
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
     * @return ProviderFilterInterface
     */
    public function providerFilter(): ProviderFilterInterface
    {
        return ProviderFilter::make($this);
    }

    /**
     * @return PaymentMethodInterface
     */
    public function paymentMethod(): PaymentMethodInterface
    {
        return PaymentMethod::make($this);
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
     * @throws Exceptions\ApiRequestJsonSerializationException
     * @throws Exceptions\ApiResponseUnsuccessfulException
     * @throws Exceptions\InvalidArgumentException
     * @throws Exceptions\SignerException
     * @throws Exceptions\ValidationException
     *
     * @return PaymentRetrievedInterface
     */
    public function getPayment(string $id): PaymentRetrievedInterface
    {
        return PaymentApi::make($this)->retrieve($id);
    }

    /**
     * @return HppInterface
     */
    public function hostedPaymentsPage(): HppInterface
    {
        $baseUrl = $this->config->shouldUseProduction()
            ? Endpoints::HPP_PROD_URL
            : Endpoints::HPP_SANDBOX_URL;

        return Hpp::make($this)->baseUrl($baseUrl);
    }

    /**
     * @return SdkConfigInterface
     */
    public static function configure(): SdkConfigInterface
    {
        return new SdkConfig(new SdkFactory());
    }
}
