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
use TrueLayer\Interfaces\Client\ConfigInterface;
use TrueLayer\Services\ApiClient\ApiClient;
use TrueLayer\Services\ApiClient\Decorators;
use TrueLayer\Services\Auth\AccessToken;
use TrueLayer\Services\Client\Client;
use TrueLayer\Services\Client\Config;

final class ClientFactory implements ClientFactoryInterface
{
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
     * @param ConfigInterface $config
     *
     * @throws SignerException
     *
     * @return ClientInterface
     */
    public function make(ConfigInterface $config): ClientInterface
    {
        $this->makeValidatorFactory();
        $this->makeHttpClient($config);
        $this->makeAuthToken($config);
        $this->makeApiClient($config);

        $apiFactory = new ApiFactory($this->apiClient);
        $entityFactory = new EntityFactory($this->validatorFactory, $apiFactory, $config);

        return new Client($this->apiClient, $apiFactory, $entityFactory);
    }

    /**
     * Build the HTTP client.
     *
     * @param ConfigInterface $config
     */
    private function makeHttpClient(ConfigInterface $config): void
    {
        $this->httpClient = $config->getHttpClient();
    }

    /**
     * Build the validation factory
     * Used for validating api requests and responses.
     */
    private function makeValidatorFactory(): void
    {
        $filesystem = new \Illuminate\Filesystem\Filesystem();
        $langPath = \dirname(__FILE__, 3) . '/lang';
        $loader = new \Illuminate\Translation\FileLoader($filesystem, $langPath);
        $loader->addNamespace('lang', $langPath);
        $loader->load('en', 'validation', 'lang');
        $translationFactory = new \Illuminate\Translation\Translator($loader, 'en');

        $this->validatorFactory = new \Illuminate\Validation\Factory($translationFactory);
    }

    /**
     * Build the auth token service
     * Handles the auth token retrieval & manages expiration.
     *
     * @param ConfigInterface $config
     */
    private function makeAuthToken(ConfigInterface $config): void
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
     * @param ConfigInterface $config
     *
     * @throws SignerException
     */
    private function makeApiClient(ConfigInterface $config): void
    {
        try {
            $signer = \TrueLayer\Signing\Signer::signWithPem(
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
     * @return ConfigInterface
     */
    public static function makeConfigurator(): ConfigInterface
    {
        return new Config(new ClientFactory());
    }
}
