<?php

declare(strict_types=1);

namespace TrueLayer\Authentication;

use Http\Client\Common\HttpMethodsClient;
use Http\Client\Common\HttpMethodsClientInterface;
use Http\Discovery\Psr17FactoryDiscovery;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use TrueLayer\Contracts\Auth\AuthApi as IAuthApi;
use TrueLayer\Contracts\Auth\Resources\AuthToken;
use TrueLayer\Options;

class AuthApi implements IAuthApi
{
    private const PRODUCTION_URL = "https://auth.truelayer.com";
    private const SANDBOX_URL = "https://auth.truelayer-sandbox.com";
    private HttpMethodsClientInterface $httpClient;
    private RequestFactoryInterface $requestFactory;
    private StreamFactoryInterface $streamFactory;
    private Options $options;

    public function __construct(\Http\Client\HttpClient $httpClient, Options $options)
    {
        $this->requestFactory = $requestFactory ?? Psr17FactoryDiscovery::findRequestFactory();
        $this->streamFactory = $streamFactory ?? Psr17FactoryDiscovery::findStreamFactory();
        $this->httpClient = new HttpMethodsClient($httpClient, $this->requestFactory, $this->streamFactory);
        $this->options = $options;
    }

    public function getAuthToken(): AuthToken
    {
        // TODO: Handle errors
        $response = $this->httpClient->post(
            self::SANDBOX_URL . '/connect/token',
            [],
            http_build_query([
                'grant_type' => 'client_credentials',
                'client_id' => $this->options->getClientId(),
                'client_secret' => $this->options->getClientSecret(),
                'scope' => 'paydirect',
            ])
        );

        return new \TrueLayer\AuthToken((string)$response->getBody());
    }
}
