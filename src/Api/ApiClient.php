<?php

declare(strict_types=1);

namespace TrueLayer\Api;

use GuzzleHttp\Psr7\Request;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface as HttpClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Ramsey\Uuid\Uuid;
use Illuminate\Contracts\Validation;
use TrueLayer\Constants\RequestMethods;
use TrueLayer\Contracts\Api\ApiClientInterface;
use TrueLayer\Contracts\Services\AuthTokenManagerInterface;
use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiRequestValidationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\ApiResponseValidationException;
use TrueLayer\Exceptions\AuthTokenRetrievalFailure;
use TrueLayer\Signing\Contracts\Signer;

class ApiClient implements ApiClientInterface
{
    const MAX_RETRIES = 1;

    /**
     * @var HttpClientInterface
     */
    private HttpClientInterface $httpClient;

    /**
     * @var AuthTokenManagerInterface
     */
    private AuthTokenManagerInterface $authTokenManager;

    /**
     * @var Signer
     */
    private Signer $signer;

    /**
     * @var Validation\Factory
     */
    private Validation\Factory $validationFactory;

    /**
     * @var string
     */
    private string $baseUri;

    /**
     * @param HttpClientInterface $httpClient
     * @param AuthTokenManagerInterface $authTokenManager
     * @param Signer $signer
     * @param string $baseUri
     */
    public function __construct(
        HttpClientInterface $httpClient,
        AuthTokenManagerInterface $authTokenManager,
        Signer $signer,
        Validation\Factory $validationFactory,
        string $baseUri
    )
    {
        $this->httpClient = $httpClient;
        $this->authTokenManager = $authTokenManager;
        $this->signer = $signer;
        $this->validationFactory = $validationFactory;
        $this->baseUri = $baseUri;
    }

    /**
     * @param string $uri
     * @param array $data
     * @param callable $requestRules
     * @param callable $responseRules
     * @return array
     * @throws ApiRequestJsonSerializationException
     * @throws ApiRequestValidationException
     * @throws ApiResponseUnsuccessfulException
     * @throws ApiResponseValidationException
     * @throws AuthTokenRetrievalFailure
     */
    public function post(string $uri, array $data, callable $requestRules, callable $responseRules): array
    {
        return $this->send(RequestMethods::POST, $uri, $data, $requestRules, $responseRules);
    }

    /**
     * @param string $uri
     * @param callable $requestRules
     * @param callable $responseRules
     * @return array
     * @throws ApiRequestJsonSerializationException
     * @throws ApiRequestValidationException
     * @throws ApiResponseUnsuccessfulException
     * @throws ApiResponseValidationException
     * @throws AuthTokenRetrievalFailure
     */
    public function get(string $uri, callable $requestRules, callable $responseRules): array
    {
        return $this->send(RequestMethods::GET, $uri, [], $requestRules, $responseRules);
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array $data
     * @param callable $requestRules
     * @param callable $responseRules
     * @return array
     * @throws ApiRequestJsonSerializationException
     * @throws ApiRequestValidationException
     * @throws ApiResponseUnsuccessfulException
     * @throws ApiResponseValidationException
     * @throws AuthTokenRetrievalFailure
     */
    public function send(string $method, string $uri, array $data, callable $requestRules, callable $responseRules): array
    {
        $validated = $this->validate($data, $requestRules($data), ApiRequestValidationException::class);

        $request = $this->createRequest($method, $uri, $validated);

        $response = $this->sendRequest($request);

        $this->validateResponseStatus($response);

        $responseData = $this->getResponseData($response);

        return $this->validate(
            $responseData,
            $responseRules($responseData),
            ApiResponseValidationException::class
        );
    }

    /**
     * @param RequestInterface $request
     * @param int $retry
     * @return ResponseInterface
     * @throws ApiRequestJsonSerializationException
     * @throws ApiRequestJsonSerializationException
     * @throws AuthTokenRetrievalFailure
     * @throws ApiResponseValidationException
     * @throws ApiRequestValidationException
     * @throws ApiResponseUnsuccessfulException
     */
    private function sendRequest(RequestInterface $request, int $retry = 0): ResponseInterface
    {
        try {
            return $this->httpClient->sendRequest($request);
        } catch (ClientExceptionInterface $e) {
            if ($retry < self::MAX_RETRIES) {
                return $this->sendRequest($request, $retry + 1);
            }
        }
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array|null $data
     * @return Request
     * @throws ApiRequestJsonSerializationException
     * @throws AuthTokenRetrievalFailure
     * @throws ApiRequestValidationException
     */
    private function createRequest(string $method, string $uri, array $data = []): Request
    {
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => "Bearer {$this->authTokenManager->getAccessToken()}",
        ];

        $endpoint = $this->baseUri . $uri;
        $body = $this->jsonEncode($data);
        $request = new Request($method, $endpoint, $headers, $body);

        if ($this->modifiesResources($method)) {
            $request = $request->withHeader('Idempotency-Key', Uuid::uuid1()->toString());
            $request = $this->signer->addSignatureHeader($request);
        }

        return $request;
    }

    /**
     * @param string $method
     * @return bool
     */
    private function modifiesResources(string $method): bool
    {
        return in_array($method, [
            RequestMethods::POST,
            RequestMethods::PUT,
            RequestMethods::PATCH,
            RequestMethods::DELETE
        ]);
    }

    /**
     * @throws ApiResponseUnsuccessfulException
     */
    private function validateResponseStatus(ResponseInterface $response): void
    {
        $statusCode = $response->getStatusCode();

        if ($statusCode >= 400) {
            throw new ApiResponseUnsuccessfulException(
                $statusCode,
                $this->getResponseData($response)
            );
        }
    }

    /**
     * @return mixed|string
     */
    private function getResponseData(ResponseInterface $response)
    {
        $encoded = $response->getBody()->getContents();
        $decoded = json_decode($encoded, true);

        if ($decoded === null && json_last_error() !== JSON_ERROR_NONE) {
            return $encoded;
        }

        return $decoded;
    }

    /**
     * @param array $data
     * @return string
     * @throws ApiRequestJsonSerializationException
     */
    private function jsonEncode(array $data): string
    {
        $encoded = \json_encode($data);

        if (\JSON_ERROR_NONE !== \json_last_error()) {
            throw new ApiRequestJsonSerializationException(\json_last_error_msg());
        }

        return $encoded;
    }

    /**
     * @param array $data
     * @param array $rules
     * @param string $throwable
     * @return array
     * @throws ApiResponseValidationException
     * @throws ApiRequestValidationException
     */
    private function validate(array $data, array $rules, string $throwable): array
    {
        $validator = $this->validationFactory->make($data, $rules);

        if ($validator->fails()) {
            throw new $throwable($validator->errors()->toArray());
        }

        return $validator->validated();
    }
}
