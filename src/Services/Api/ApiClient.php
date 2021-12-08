<?php

declare(strict_types=1);

namespace TrueLayer\Services\Api;

use GuzzleHttp\Psr7\Request;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface as HttpClientInterface;
use Psr\Http\Message\ResponseInterface;
use TrueLayer\Contracts\Api\ApiClientInterface;
use TrueLayer\Contracts\Api\ApiRequestInterface;
use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;

class ApiClient implements ApiClientInterface
{
    /**
     * @var HttpClientInterface
     */
    private HttpClientInterface $httpClient;

    /**
     * @var string
     */
    private string $baseUri;

    /**
     * @param HttpClientInterface             $httpClient
     * @param string                          $baseUri
     */
    public function __construct(HttpClientInterface $httpClient, string $baseUri)
    {
        $this->httpClient = $httpClient;
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
     * @return array
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     * @throws ClientExceptionInterface
     */
    public function send(ApiRequestInterface $apiRequest): array
    {
        $headers = array_merge($apiRequest->getHeaders(), [
            'Content-Type' => 'application/json',
        ]);

        $httpRequest = new Request(
            $apiRequest->getMethod(),
            $this->baseUri . $apiRequest->getUri(),
            $headers,
            $apiRequest->getJsonPayload()
        );

        $response = $this->httpClient->sendRequest($httpRequest);

        $data = $this->getResponseData($response);

        if ($response->getStatusCode() >= 400) {
            throw new ApiResponseUnsuccessfulException($response->getStatusCode(), $data);
        }

        return $data;
    }

    /**
     * @param ResponseInterface $response
     * @return array
     */
    private function getResponseData(ResponseInterface $response): array
    {
        $encoded = $response->getBody()->getContents();
        $decoded = \json_decode($encoded, true);

        if ($decoded === null && \json_last_error() !== JSON_ERROR_NONE) {
            return (array) $encoded;
        }

        return (array) $decoded;
    }
}
