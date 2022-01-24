<?php

declare(strict_types=1);

namespace TrueLayer\Services\Sdk;

use TrueLayer\Exceptions;
use TrueLayer\Interfaces\ApiClient\ApiClientInterface;
use TrueLayer\Interfaces\Beneficiary\BeneficiaryBuilderInterface;
use TrueLayer\Interfaces\Factories\ApiFactoryInterface;
use TrueLayer\Interfaces\Factories\EntityFactoryInterface;
use TrueLayer\Interfaces\HppInterface;
use TrueLayer\Interfaces\MerchantAccount\MerchantAccountInterface;
use TrueLayer\Interfaces\Payment\PaymentMethodInterface;
use TrueLayer\Interfaces\Payment\PaymentRequestInterface;
use TrueLayer\Interfaces\Payment\PaymentRetrievedInterface;
use TrueLayer\Interfaces\Provider\ProviderFilterInterface;
use TrueLayer\Interfaces\Sdk\SdkConfigInterface;
use TrueLayer\Interfaces\Sdk\SdkInterface;
use TrueLayer\Interfaces\UserInterface;

final class Sdk implements SdkInterface
{
    /**
     * @var ApiClientInterface
     */
    private ApiClientInterface $apiClient;

    /**
     * @var ApiFactoryInterface
     */
    private ApiFactoryInterface $apiFactory;

    /**
     * @var EntityFactoryInterface
     */
    private EntityFactoryInterface $entityFactory;

    /**
     * @var SdkConfigInterface
     */
    private SdkConfigInterface $config;

    /**
     * @param ApiClientInterface     $apiClient
     * @param ApiFactoryInterface    $apiFactory
     * @param EntityFactoryInterface $entityFactory
     * @param SdkConfigInterface     $config
     */
    public function __construct(
        ApiClientInterface $apiClient,
        ApiFactoryInterface $apiFactory,
        EntityFactoryInterface $entityFactory,
        SdkConfigInterface $config
    ) {
        $this->apiClient = $apiClient;
        $this->apiFactory = $apiFactory;
        $this->entityFactory = $entityFactory;
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
     * @throws Exceptions\InvalidArgumentException
     * @throws Exceptions\ValidationException
     *
     * @return UserInterface
     */
    public function user(): UserInterface
    {
        return $this->entityFactory->make(UserInterface::class);
    }

    /**
     * @throws Exceptions\InvalidArgumentException
     * @throws Exceptions\ValidationException
     *
     * @return BeneficiaryBuilderInterface
     */
    public function beneficiary(): BeneficiaryBuilderInterface
    {
        return $this->entityFactory->make(BeneficiaryBuilderInterface::class);
    }

    /**
     * @throws Exceptions\InvalidArgumentException
     * @throws Exceptions\ValidationException
     *
     * @return ProviderFilterInterface
     */
    public function providerFilter(): ProviderFilterInterface
    {
        return $this->entityFactory->make(ProviderFilterInterface::class);
    }

    /**
     * @throws Exceptions\InvalidArgumentException
     * @throws Exceptions\ValidationException
     *
     * @return PaymentMethodInterface
     */
    public function paymentMethod(): PaymentMethodInterface
    {
        return $this->entityFactory->make(PaymentMethodInterface::class);
    }

    /**
     * @throws Exceptions\InvalidArgumentException
     * @throws Exceptions\ValidationException
     *
     * @return PaymentRequestInterface
     */
    public function payment(): PaymentRequestInterface
    {
        return $this->entityFactory->make(PaymentRequestInterface::class);
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
        $data = $this->apiFactory->paymentsApi()->retrieve($id);

        return $this->entityFactory->make(PaymentRetrievedInterface::class, $data);
    }

    /**
     * @throws Exceptions\InvalidArgumentException
     * @throws Exceptions\ValidationException
     *
     * @return HppInterface
     */
    public function hostedPaymentsPage(): HppInterface
    {
        return $this->entityFactory->make(HppInterface::class);
    }

    /**
     * @throws Exceptions\ApiRequestJsonSerializationException
     * @throws Exceptions\ApiResponseUnsuccessfulException
     * @throws Exceptions\InvalidArgumentException
     * @throws Exceptions\SignerException
     * @throws Exceptions\ValidationException
     *
     * @return MerchantAccountInterface[]
     */
    public function getMerchantAccounts(): array
    {
        $data = $this->apiFactory->merchantAccountsApi()->list();

        return $this->entityFactory->makeMany(MerchantAccountInterface::class, $data);
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
     * @return MerchantAccountInterface
     */
    public function getMerchantAccount(string $id): MerchantAccountInterface
    {
        $data = $this->apiFactory->merchantAccountsApi()->retrieve($id);

        return $this->entityFactory->make(MerchantAccountInterface::class, $data);
    }
}
