<?php

declare(strict_types=1);

namespace TrueLayer\Factories;

use TrueLayer\Interfaces\Api\MerchantAccountsApiInterface;
use TrueLayer\Interfaces\Api\PaymentsApiInterface;
use TrueLayer\Interfaces\Api\PayoutsApiInterface;
use TrueLayer\Interfaces\ApiClient\ApiClientInterface;
use TrueLayer\Interfaces\Factories\ApiFactoryInterface;
use TrueLayer\Services\Api\MerchantAccountsApi;
use TrueLayer\Services\Api\PaymentsApi;
use TrueLayer\Services\Api\PayoutsApi;

final class ApiFactory implements ApiFactoryInterface
{
    /**
     * @var ApiClientInterface
     */
    private ApiClientInterface $apiClient;

    /**
     * @param ApiClientInterface $apiClient
     */
    public function __construct(ApiClientInterface $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    /**
     * @return PaymentsApiInterface
     */
    public function paymentsApi(): PaymentsApiInterface
    {
        return new PaymentsApi($this->apiClient);
    }

    /**
     * @return MerchantAccountsApiInterface
     */
    public function merchantAccountsApi(): MerchantAccountsApiInterface
    {
        return new MerchantAccountsApi($this->apiClient);
    }

    /**
     * @return PayoutsApiInterface
     */
    public function payoutsApi(): PayoutsApiInterface
    {
        return new PayoutsApi($this->apiClient);
    }
}
