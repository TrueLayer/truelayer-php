<?php

declare(strict_types=1);

namespace TrueLayer\Services\ApiClient;

use TrueLayer\Constants\RequestMethods;
use TrueLayer\Contracts\ApiClient\ApiClientInterface;
use TrueLayer\Contracts\ApiClient\ApiRequestInterface;
use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\SignerException;

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
     * @var mixed[]
     */
    private array $payload = [];

    /**
     * @var mixed[]
     */
    private array $headers = [];

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
     * @param mixed[] $payload
     *
     * @return ApiRequestInterface
     */
    public function payload(array $payload): ApiRequestInterface
    {
        $this->payload = $payload;

        return $this;
    }

    /**
     * @return mixed[]
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

        if (\JSON_ERROR_NONE !== \json_last_error() || $encoded === false) {
            throw new ApiRequestJsonSerializationException(\json_last_error_msg());
        }

        return $encoded;
    }

    /**
     * @return mixed[]
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
     *@throws ApiResponseUnsuccessfulException
     * @throws SignerException
     * @throws ApiRequestJsonSerializationException
     *
     * @return mixed
     */
    public function post()
    {
        $this->method = RequestMethods::POST;

        return $this->apiClient->send($this);
    }

    /**
     *@throws ApiResponseUnsuccessfulException
     * @throws SignerException
     * @throws ApiRequestJsonSerializationException
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
