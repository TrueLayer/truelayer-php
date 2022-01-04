<?php

declare(strict_types=1);

namespace TrueLayer\Services\Api;

use Closure;
use TrueLayer\Constants\RequestMethods;
use TrueLayer\Contracts\Api\ApiClientInterface;
use TrueLayer\Contracts\Api\ApiRequestInterface;
use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiRequestValidationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\ApiResponseValidationException;

final class ApiRequest implements ApiRequestInterface
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
     * @var array
     */
    private array $payload = [];

    /**
     * @var array
     */
    private array $headers = [];

    /**
     * @var Closure|null
     */
    private ?Closure $requestRulesFactory = null;

    /**
     * @var Closure|null
     */
    private ?Closure $responseRulesFactory = null;

    /**
     * @var string
     */
    private string $method = RequestMethods::GET;

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
     * @return string
     */
    public function getUri(): string
    {
        return (string) $this->uri;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
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
     * @return array
     */
    public function getPayload(): array
    {
        return $this->payload;
    }

    /**
     * @throws ApiRequestJsonSerializationException
     *
     * @return string
     */
    public function getJsonPayload(): string
    {
        $encoded = \json_encode($this->getPayload());

        if (\JSON_ERROR_NONE !== \json_last_error()) {
            throw new ApiRequestJsonSerializationException(\json_last_error_msg());
        }

        return $encoded;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @param string $key
     * @param string $value
     *
     * @return ApiRequestInterface
     */
    public function addHeader(string $key, string $value): ApiRequestInterface
    {
        $this->headers[$key] = $value;

        return $this;
    }

    /**
     * @return Closure
     */
    public function getRequestRules(): ?Closure
    {
        return $this->requestRulesFactory;
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
     * @return Closure|null
     */
    public function getResponseRules(): ?Closure
    {
        return $this->responseRulesFactory;
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
     * @return mixed
     */
    public function post()
    {
        $this->method = RequestMethods::POST;

        return $this->apiClient->send($this);
    }

    /**
     * @throws ApiRequestJsonSerializationException
     * @throws ApiRequestValidationException
     * @throws ApiResponseUnsuccessfulException
     * @throws ApiResponseValidationException
     *
     * @return mixed
     */
    public function get()
    {
        $this->method = RequestMethods::GET;

        return $this->apiClient->send($this);
    }

    /**
     * @return bool
     */
    public function modifiesResources(): bool
    {
        return \in_array($this->getMethod(), [
            RequestMethods::POST,
            RequestMethods::PUT,
            RequestMethods::PATCH,
            RequestMethods::DELETE,
        ]);
    }
}
