<?php

declare(strict_types=1);

namespace TrueLayer\Services\Api;

use TrueLayer\Interfaces\ApiClient\ApiClientInterface;
use TrueLayer\Interfaces\ApiClient\ApiRequestInterface;

abstract class Api
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
     * @return ApiRequestInterface
     */
    protected function request(): ApiRequestInterface
    {
        return $this->apiClient->request();
    }
}
