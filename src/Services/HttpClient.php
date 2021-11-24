<?php

declare(strict_types=1);

namespace TrueLayer\Services;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use TrueLayer\Contracts\Services\AuthTokenManagerInterface;
use TrueLayer\Contracts\Services\HttpClientInterface;
use TrueLayer\Exceptions\InvalidRequestData;
use TrueLayer\Exceptions\MissingAuthTokenException;
use TrueLayer\Signing\Contracts\Signer;

class HttpClient implements HttpClientInterface
{
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
     * @param string $baseUri
     */
    public function __construct(ClientInterface $client, string $baseUri)
    {
        $this->client = $client;
        $this->baseUri = $baseUri;
    }

    /**
     * @param Signer $signer
     * @return HttpClientInterface
     */
    public function signer(Signer $signer): HttpClientInterface
    {
        $this->signer = $signer;
        return $this;
    }

    /**
     * @param bool withSignature
     * @return HttpClientInterface
     */
    public function withSignature(bool $withSignature = true): HttpClientInterface
    {
        $this->withSignature = $withSignature;
        return $this;
    }

    /**
     * @param AuthTokenManagerInterface $authTokenManager
     * @return HttpClientInterface
     */
    public function authTokenManager(AuthTokenManagerInterface $authTokenManager): HttpClientInterface
    {
        $this->authTokenManager = $authTokenManager;
        return $this;
    }

    /**
     * @param bool $withAuthToken
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
     * @return ResponseInterface
     * @throws GuzzleException
     * @throws \TrueLayer\Exceptions\AuthTokenRetrievalFailure
     */
    public function get($uri, array $data = [], array $headers = []): ResponseInterface
    {
        return $this->send('GET', $uri, $data, $headers);
    }

    /**
     * @param $uri
     * @param array $data
     * @param array $headers
     * @return ResponseInterface
     * @throws GuzzleException
     * @throws \TrueLayer\Exceptions\AuthTokenRetrievalFailure
     */
    public function post($uri, array $data = [], array $headers = []): ResponseInterface
    {
        return $this->send('POST', $uri, $data, $headers);
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array $data
     * @param array $headers
     * @return ResponseInterface
     * @throws InvalidRequestData
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @throws \TrueLayer\Exceptions\AuthTokenRetrievalFailure
     */
    private function send(string $method, string $uri, array $data = [], array $headers = []): ResponseInterface
    {
        $headers = [
            'Content-Type' => 'application/json',
            'Idempotency-Key' => '123'
        ];

        $body = \json_encode($data);
        if (\JSON_ERROR_NONE !== \json_last_error()) {
            throw new InvalidRequestData(\json_last_error_msg());
        }

        $request = new Request($method, $this->baseUri . $uri, $headers, $body);

        if ($this->withAuthToken) {
            $token = $this->authTokenManager->getAccessToken();
            $request = $request->withHeader('Authorization', "Bearer $token");
            $this->withAuthToken = false;
        }

        if ($this->withSignature) {
            $request = $this->signer->addSignatureHeader($request);
            $this->withSignature = false;
        }

        return $this->client->sendRequest($request);
    }
}
