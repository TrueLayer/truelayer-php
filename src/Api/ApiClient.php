<?php

declare(strict_types=1);

namespace TrueLayer\Api;

use GuzzleHttp\Psr7\Request;
use Illuminate\Contracts\Validation;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface as HttpClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Ramsey\Uuid\Uuid;
use TrueLayer\Constants\RequestMethods;
use TrueLayer\Contracts\Api\ApiClientInterface;
use TrueLayer\Contracts\Api\ApiRequestInterface;
use TrueLayer\Contracts\Api\Handlers\AuthTokenRetrieveInterface;
use TrueLayer\Contracts\Models\AuthTokenInterface;
use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiRequestValidationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\ApiResponseValidationException;
use TrueLayer\Signing\Contracts\Signer;

class ApiClient implements ApiClientInterface
{
    public const MAX_RETRIES = 1;

    /**
     * @var HttpClientInterface
     */
    private HttpClientInterface $httpClient;

    /**
     * @var AuthTokenRetrieveInterface|null
     */
    private ?AuthTokenRetrieveInterface $authTokenRetrieve = null;

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
     * @var AuthTokenInterface|null
     */
    private ?AuthTokenInterface $authToken = null;

    /**
     * @param string                          $baseUri
     * @param HttpClientInterface             $httpClient
     * @param Signer                          $signer
     * @param Validation\Factory              $validationFactory
     * @param AuthTokenRetrieveInterface|null $authTokenRetrieve
     */
    public function __construct(
        string $baseUri,
        HttpClientInterface $httpClient,
        Signer $signer,
        Validation\Factory $validationFactory,
        AuthTokenRetrieveInterface $authTokenRetrieve = null
    ) {
        $this->baseUri = $baseUri;
        $this->httpClient = $httpClient;
        $this->signer = $signer;
        $this->validationFactory = $validationFactory;
        $this->authTokenRetrieve = $authTokenRetrieve;
    }

    /**
     * @return ApiRequestInterface
     */
    public function request(): ApiRequestInterface
    {
        return new ApiRequest($this);
    }

    /**
     * @param string        $method
     * @param string        $uri
     * @param array         $data
     * @param callable|null $requestRules
     * @param callable|null $responseRules
     *
     * @throws ApiRequestJsonSerializationException
     * @throws ApiRequestValidationException
     * @throws ApiResponseUnsuccessfulException
     * @throws ApiResponseValidationException
     *
     * @return array
     */
    public function send(string $method, string $uri, array $data, callable $requestRules = null, callable $responseRules = null): array
    {
        if ($requestRules) {
            $data = $this->validate($data, $requestRules($data), ApiRequestValidationException::class);
        }

        $request = $this->createRequest($method, $uri, $data);
        $response = $this->sendHttpRequest($request);

        $this->validateResponseStatus($response);
        $responseData = $this->getResponseData($response);

        if ($responseRules) {
            $responseData = $this->validate($responseData, $responseRules($responseData), ApiResponseValidationException::class);
        }

        return $responseData;
    }

    /**
     * @param RequestInterface $request
     * @param int              $retry
     *
     * @throws ApiRequestJsonSerializationException
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseValidationException
     * @throws ApiRequestValidationException
     * @throws ApiResponseUnsuccessfulException
     *
     * @return ResponseInterface
     */
    private function sendHttpRequest(RequestInterface $request, int $retry = 0): ResponseInterface
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
     * @param array  $data
     *
     * @throws ApiRequestJsonSerializationException
     * @throws ApiRequestValidationException
     * @throws ApiResponseUnsuccessfulException
     * @throws ApiResponseValidationException
     *
     * @return Request
     */
    private function createRequest(string $method, string $uri, array $data = []): Request
    {
        $headers = [
            'Content-Type' => 'application/json',
        ];

        if ($token = $this->getAccessToken()) {
            $headers['Authorization'] = "Bearer {$token}";
        }

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
     * @throws ApiRequestJsonSerializationException
     * @throws ApiRequestValidationException
     * @throws ApiResponseUnsuccessfulException
     * @throws ApiResponseValidationException
     *
     * @return string|null
     */
    private function getAccessToken(): ?string
    {
        if (!$this->authTokenRetrieve) {
            return null;
        }

        if (!$this->authToken || $this->authToken->isExpired()) {
            $this->authToken = $this->authTokenRetrieve->execute();
        }

        return $this->authToken->getAccessToken();
    }

    /**
     * @param string $method
     *
     * @return bool
     */
    private function modifiesResources(string $method): bool
    {
        return \in_array($method, [
            RequestMethods::POST,
            RequestMethods::PUT,
            RequestMethods::PATCH,
            RequestMethods::DELETE,
        ]);
    }

    /**
     * @throws ApiResponseUnsuccessfulException
     */
    private function validateResponseStatus(ResponseInterface $response): void
    {
        $statusCode = $response->getStatusCode();

        if ($statusCode >= 400) {
            throw new ApiResponseUnsuccessfulException($statusCode, $this->getResponseData($response));
        }
    }

    /**
     * @return mixed|string
     */
    private function getResponseData(ResponseInterface $response)
    {
        $encoded = $response->getBody()->getContents();
        $decoded = \json_decode($encoded, true);

        if ($decoded === null && \json_last_error() !== JSON_ERROR_NONE) {
            return $encoded;
        }

        return $decoded;
    }

    /**
     * @param array $data
     *
     * @throws ApiRequestJsonSerializationException
     *
     * @return string
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
     * @param array  $data
     * @param array  $rules
     * @param string $throwable
     *
     * @throws ApiResponseValidationException
     * @throws ApiRequestValidationException
     *
     * @return array
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
