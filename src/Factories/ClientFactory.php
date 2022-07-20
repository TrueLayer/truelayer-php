<?php

declare(strict_types=1);

namespace TrueLayer\Factories;

use Illuminate\Contracts\Validation\Factory as ValidatorFactory;
use Psr\Http\Client\ClientInterface as HttpClientInterface;
use TrueLayer\Constants\Endpoints;
use TrueLayer\Exceptions\SignerException;
use TrueLayer\Interfaces\ApiClient\ApiClientInterface;
use TrueLayer\Interfaces\Auth\AccessTokenInterface;
use TrueLayer\Interfaces\Client\ClientFactoryInterface;
use TrueLayer\Interfaces\Client\ClientInterface;
use TrueLayer\Interfaces\Configuration\ClientConfigInterface;
use TrueLayer\Services\ApiClient\ApiClient;
use TrueLayer\Services\ApiClient\Decorators;
use TrueLayer\Services\Auth\AccessToken;
use TrueLayer\Services\Client\Client;
use TrueLayer\Services\Client\ClientConfig;
use TrueLayer\Signing\Signer;
use TrueLayer\Traits\MakeValidatorFactory;

final class ClientFactory implements ClientFactoryInterface
{
    use MakeValidatorFactory;

    /**
     * @var ValidatorFactory
     */
    private ValidatorFactory $validatorFactory;

    /**
     * @var HttpClientInterface
     */
    private HttpClientInterface $httpClient;

    /**
     * @var AccessTokenInterface
     */
    private AccessTokenInterface $authToken;

    /**
     * @var ApiClientInterface
     */
    private ApiClientInterface $apiClient;

    /**
     * @param ClientConfigInterface $config
     *
     * @return ClientInterface
     * @throws SignerException
     *
     */
    public function make(ClientConfigInterface $config): ClientInterface
    {
        $this->validatorFactory = $this->makeValidatorFactory();
        $this->makeHttpClient($config);
        $this->makeAuthToken($config);
        $this->makeApiClient($config);

        $apiFactory = new ApiFactory($this->apiClient);
        $entityFactory = new EntityFactory($this->validatorFactory, $config, $apiFactory);

        return new Client(
            $this->apiClient,
            $apiFactory,
            $entityFactory,
            $config
        );
    }

    /**
     * Build the HTTP client.
     *
     * @param ClientConfigInterface $config
     */
    private function makeHttpClient(ClientConfigInterface $config): void
    {
        $this->httpClient = $config->getHttpClient();
    }

    /**
     * Build the auth token service
     * Handles the auth token retrieval & manages expiration.
     *
     * @param ClientConfigInterface $config
     */
    private function makeAuthToken(ClientConfigInterface $config): void
    {
        $authBaseUri = $config->shouldUseProduction()
            ? Endpoints::AUTH_PROD_URL
            : Endpoints::AUTH_SANDBOX_URL;

        $authClient = new ApiClient($this->httpClient, $authBaseUri);
        $authClient = new Decorators\ExponentialBackoffDecorator($authClient);
        $authClient = new Decorators\TLAgentDecorator($authClient);

        $this->authToken = new AccessToken(
            $authClient,
            $config->getCache(),
            $this->validatorFactory,
            $config->getClientId(),
            $config->getClientSecret(),
            ['payments', 'paydirect'],
        );
    }

    /**
     * Build the API client
     * Handles API calls, including signing, validation & error handling.
     *
     * @param ClientConfigInterface $config
     *
     * @throws SignerException
     */
    private function makeApiClient(ClientConfigInterface $config): void
    {
        try {
            $signer = Signer::signWithPem(
                $config->getKeyId(),
                $config->getPem(),
                $config->getPassphrase()
            );
        } catch (\Exception $e) {
            throw new SignerException($e->getMessage(), $e->getCode(), $e);
        }

        $apiBaseUri = $config->shouldUseProduction()
            ? Endpoints::API_PROD_URL
            : Endpoints::API_SANDBOX_URL;

        $this->apiClient = new ApiClient($this->httpClient, $apiBaseUri);
        $this->apiClient = new Decorators\AccessTokenDecorator($this->apiClient, $this->authToken);
        $this->apiClient = new Decorators\ExponentialBackoffDecorator($this->apiClient);
        $this->apiClient = new Decorators\SigningDecorator($this->apiClient, $signer);
        $this->apiClient = new Decorators\IdempotencyKeyDecorator($this->apiClient);
        $this->apiClient = new Decorators\TLAgentDecorator($this->apiClient);
    }

    /**
     * @return ClientConfigInterface
     */
    public static function makeConfigurator(): ClientConfigInterface
    {
        return new ClientConfig(new ClientFactory());
    }
}
