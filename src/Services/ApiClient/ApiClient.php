<?php

declare(strict_types=1);

namespace TrueLayer\Services\ApiClient;

use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface as HttpClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use TrueLayer\Constants\CustomHeaders;
use TrueLayer\Constants\ResponseStatusCodes;
use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Interfaces\ApiClient\ApiClientInterface;
use TrueLayer\Interfaces\ApiClient\ApiRequestInterface;
use TrueLayer\Settings;

final class ApiClient implements ApiClientInterface
{
    /**
     * @var HttpClientInterface
     */
    private HttpClientInterface $httpClient;

    /**
     * @var RequestFactoryInterface
     */
    private RequestFactoryInterface $httpRequestFactory;

    /**
     * @var string
     */
    private string $baseUri;

    /**
     * @param HttpClientInterface $httpClient
     * @param RequestFactoryInterface $httpRequestFactory
     * @param string $baseUri
     */
    public function __construct(HttpClientInterface $httpClient, RequestFactoryInterface $httpRequestFactory, string $baseUri)
    {
        $this->httpClient = $httpClient;
        $this->httpRequestFactory = $httpRequestFactory;
        $this->baseUri = $baseUri;
    }

    /**
     * @return ApiRequestInterface
     */
    public function request(): ApiRequestInterface
    {
        return new ApiRequest($this);
    }

    /**
     * @param ApiRequestInterface $apiRequest
     *
     * @return mixed
     * @throws ClientExceptionInterface
     * @throws ApiRequestJsonSerializationException
     *
     * @throws ApiResponseUnsuccessfulException
     */
    public function send(ApiRequestInterface $apiRequest)
    {
        $httpRequest = $this->httpRequestFactory->createRequest($apiRequest->getMethod(), $this->baseUri . $apiRequest->getUri());
        $httpRequest->getBody()->write($apiRequest->getJsonPayload());

        $headers = $apiRequest->getHeaders();
        $headers['Content-Type'] = 'application/json';

        if ($tlAgent = Settings::getTlAgent()) {
            $headers[CustomHeaders::TL_AGENT] = $tlAgent;
        }

        foreach ($headers as $key => $val) {
            $httpRequest = $httpRequest->withHeader($key, $val);
        }

        $response = $this->httpClient->sendRequest($httpRequest);

        $data = $this->getResponseData($response);

        if ($response->getStatusCode() >= ResponseStatusCodes::BAD_REQUEST) {
            throw new ApiResponseUnsuccessfulException($response->getStatusCode(), $data, $response->getHeaders());
        }

        return $data;
    }

    /**
     * @param ResponseInterface $response
     *
     * @return mixed
     */
    private function getResponseData(ResponseInterface $response)
    {
        $response->getBody()->rewind();
        $encoded = $response->getBody()->getContents();
        $decoded = \json_decode($encoded, true);
        $isError = $decoded === null && \json_last_error() !== JSON_ERROR_NONE;

        return $isError ? $encoded : $decoded;
    }
}
