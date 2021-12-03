<?php

declare(strict_types=1);

namespace TrueLayer\Api;

use Closure;
use TrueLayer\Constants\RequestMethods;
use TrueLayer\Contracts\Api\ApiClientInterface;
use TrueLayer\Contracts\Api\ApiRequestInterface;
use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiRequestValidationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\ApiResponseValidationException;

class ApiRequest implements ApiRequestInterface
{
    /**
     * @var ApiClientInterface
     */
    private ApiClientInterface $apiClient;

    /**
     * @var string
     */
    private string $uri;

    /**
     * @var array|null
     */
    private array $payload = [];

    /**
     * @var Closure|null
     */
    private ?Closure $requestRulesFactory = null;

    /**
     * @var Closure|null
     */
    private ?Closure $responseRulesFactory = null;

    /**
     * @param ApiClientInterface $apiClient
     */
    public function __construct(ApiClientInterface $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    /**
     * @param string $uri
     *
     * @return ApiRequestInterface
     */
    public function uri(string $uri): ApiRequestInterface
    {
        $this->uri = $uri;

        return $this;
    }

    /**
     * @param array $payload
     *
     * @return ApiRequestInterface
     */
    public function payload(array $payload): ApiRequestInterface
    {
        $this->payload = $payload;

        return $this;
    }

    /**
     * @param callable $factory
     *
     * @return ApiRequestInterface
     */
    public function requestRules(callable $factory): ApiRequestInterface
    {
        $this->requestRulesFactory = Closure::fromCallable($factory);

        return $this;
    }

    /**
     * @param callable $factory
     *
     * @return ApiRequestInterface
     */
    public function responseRules(callable $factory): ApiRequestInterface
    {
        $this->responseRulesFactory = Closure::fromCallable($factory);

        return $this;
    }

    /**
     * @throws ApiRequestJsonSerializationException
     * @throws ApiRequestValidationException
     * @throws ApiResponseUnsuccessfulException
     * @throws ApiResponseValidationException
     *
     * @return array
     */
    public function post(): array
    {
        return $this->send(RequestMethods::POST);
    }

    /**
     * @throws ApiRequestJsonSerializationException
     * @throws ApiRequestValidationException
     * @throws ApiResponseUnsuccessfulException
     * @throws ApiResponseValidationException
     *
     * @return array
     */
    public function get(): array
    {
        return $this->send(RequestMethods::GET);
    }

    /**
     * @param string $method
     *
     * @throws ApiRequestJsonSerializationException
     * @throws ApiRequestValidationException
     * @throws ApiResponseUnsuccessfulException
     * @throws ApiResponseValidationException
     *
     * @return array
     */
    public function send(string $method): array
    {
        return $this->apiClient->send(
            $method,
            $this->uri,
            $this->payload,
            $this->requestRulesFactory,
            $this->responseRulesFactory
        );
    }
}
