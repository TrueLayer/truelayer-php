<?php

declare(strict_types=1);

namespace TrueLayer\Services;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Ramsey\Uuid\Uuid;
use TrueLayer\Contracts\Services\AuthTokenManagerInterface;
use TrueLayer\Contracts\Services\HttpClientInterface;
use TrueLayer\Exceptions\InvalidRequestData;
use TrueLayer\Signing\Contracts\Signer;

class HttpClient implements HttpClientInterface
{
    const MAX_RETRIES = 1;

    /**
     * @var ClientInterface
     */
    private ClientInterface $client;

    /**
     * @var string
     */
    private string $baseUri;

    /**
     * @var Signer
     */
    private Signer $signer;

    /**
     * @var bool
     */
    private bool $withSignature = false;

    /**
     * @var bool
     */
    private bool $withAuthToken = false;

    /**
     * @var AuthTokenManagerInterface
     */
    private AuthTokenManagerInterface $authTokenManager;

    /**
     * @param ClientInterface $client
     * @param string          $baseUri
     */
    public function __construct(ClientInterface $client, string $baseUri)
    {
        $this->client = $client;
        $this->baseUri = $baseUri;
    }

    /**
     * @param Signer $signer
     *
     * @return HttpClientInterface
     */
    public function signer(Signer $signer): HttpClientInterface
    {
        $this->signer = $signer;

        return $this;
    }

    /**
     * @param bool withSignature
     *
     * @return HttpClientInterface
     */
    public function withSignature(bool $withSignature = true): HttpClientInterface
    {
        $this->withSignature = $withSignature;

        return $this;
    }

    /**
     * @param AuthTokenManagerInterface $authTokenManager
     *
     * @return HttpClientInterface
     */
    public function authTokenManager(AuthTokenManagerInterface $authTokenManager): HttpClientInterface
    {
        $this->authTokenManager = $authTokenManager;

        return $this;
    }

    /**
     * @param bool $withAuthToken
     *
     * @return HttpClientInterface
     */
    public function withAuthToken(bool $withAuthToken = true): HttpClientInterface
    {
        $this->withAuthToken = $withAuthToken;

        return $this;
    }

    /**
     * @param $uri
     * @param array $data
     * @param array $headers
     *
     * @throws GuzzleException
     * @throws \TrueLayer\Exceptions\AuthTokenRetrievalFailure
     *
     * @return ResponseInterface
     */
    public function get($uri, array $data = [], array $headers = []): ResponseInterface
    {
        return $this->send('GET', $uri, $data, $headers);
    }

    /**
     * @param $uri
     * @param array $data
     * @param array $headers
     *
     * @throws GuzzleException
     * @throws \TrueLayer\Exceptions\AuthTokenRetrievalFailure
     *
     * @return ResponseInterface
     */
    public function post($uri, array $data = [], array $headers = []): ResponseInterface
    {
        return $this->send('POST', $uri, $data, $headers);
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array  $data
     * @param array  $headers
     *
     * @throws InvalidRequestData
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @throws \TrueLayer\Exceptions\AuthTokenRetrievalFailure
     *
     * @return ResponseInterface
     */
    private function send(string $method, string $uri, array $data = [], array $headers = []): ResponseInterface
    {
        $headers = [
            'Content-Type' => 'application/json',
            'Idempotency-Key' => Uuid::uuid1()->toString(),
        ];

        $body = \json_encode($data);
        if (\JSON_ERROR_NONE !== \json_last_error()) {
            throw new InvalidRequestData(\json_last_error_msg());
        }

        $request = new Request($method, $this->baseUri . $uri, $headers, $body);

        if ($this->withAuthToken) {
            $token = $this->authTokenManager->getAccessToken();
            $request = $request->withHeader('Authorization', "Bearer {$token}");
            $this->withAuthToken = false;
        }

        if ($this->withSignature) {
            $request = $this->signer->addSignatureHeader($request);
            $this->withSignature = false;
        }

        return $this->sendRequest($request);
    }

    /**
     * Send the request and retry in case of client failures.
     * Note: Non 200 response status codes will not trigger a retry
     * @param Request $request
     * @param int $retry
     */
    private function sendRequest(Request $request, int $retry = 0): ResponseInterface
    {
        try {
            return $this->client->sendRequest($request);
        } catch (ClientExceptionInterface $e) {
            if ($retry < self::MAX_RETRIES) {
                return $this->sendRequest($request, $retry + 1);
            }
        }
    }
}
