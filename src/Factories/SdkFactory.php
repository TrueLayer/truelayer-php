<?php

declare(strict_types=1);

namespace TrueLayer\Factories;

use Illuminate\Contracts\Validation\Factory as ValidatorFactory;
use Psr\Http\Client\ClientInterface;
use TrueLayer\Constants\Endpoints;
use TrueLayer\Interfaces\ApiClient\ApiClientInterface;
use TrueLayer\Interfaces\Auth\AccessTokenInterface;
use TrueLayer\Interfaces\Sdk\SdkConfigInterface;
use TrueLayer\Interfaces\Sdk\SdkFactoryInterface;
use TrueLayer\Interfaces\Sdk\SdkInterface;
use TrueLayer\Exceptions\SignerException;
use TrueLayer\Services\ApiClient\ApiClient;
use TrueLayer\Services\ApiClient\Decorators;
use TrueLayer\Services\Auth\AccessToken;
use TrueLayer\Services\Sdk\Sdk;
use TrueLayer\Services\Sdk\SdkConfig;

final class SdkFactory implements SdkFactoryInterface
{
    /**
     * @var ValidatorFactory
     */
    private ValidatorFactory $validatorFactory;

    /**
     * @var ClientInterface
     */
    private ClientInterface $httpClient;

    /**
     * @var AccessTokenInterface
     */
    private AccessTokenInterface $authToken;

    /**
     * @var ApiClientInterface
     */
    private ApiClientInterface $apiClient;

    /**
     * @param SdkConfigInterface $config
     *
     * @throws SignerException
     *
     * @return SdkInterface
     */
    public function make(SdkConfigInterface $config): SdkInterface
    {
        $this->makeValidatorFactory();
        $this->makeHttpClient($config);
        $this->makeAuthToken($config);
        $this->makeApiClient($config);

        $apiFactory = new ApiFactory($this->apiClient);
        $entityFactory = new EntityFactory($this->validatorFactory, $apiFactory, $config);

        return new Sdk($this->apiClient, $apiFactory, $entityFactory, $config);
    }

    /**
     * Build the HTTP client.
     *
     * @param SdkConfigInterface $config
     */
    private function makeHttpClient(SdkConfigInterface $config): void
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
        $loader = new \Illuminate\Translation\FileLoader($filesystem, \dirname(__FILE__, 4) . '/lang');
        $loader->addNamespace('lang', \dirname(__FILE__, 4) . '/lang');
        $loader->load('en', 'validation', 'lang');
        $translationFactory = new \Illuminate\Translation\Translator($loader, 'en');

        $this->validatorFactory = new \Illuminate\Validation\Factory($translationFactory);
    }

    /**
     * Build the auth token service
     * Handles the auth token retrieval & manages expiration.
     *
     * @param SdkConfigInterface $config
     */
    private function makeAuthToken(SdkConfigInterface $config): void
    {
        $authBaseUri = $config->shouldUseProduction()
            ? Endpoints::AUTH_PROD_URL
            : Endpoints::AUTH_SANDBOX_URL;

        $authClient = new ApiClient($this->httpClient, $authBaseUri);
        $authClient = new Decorators\ExponentialBackoffDecorator($authClient);

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
     * @param SdkConfigInterface $config
     *
     * @throws SignerException
     */
    private function makeApiClient(SdkConfigInterface $config): void
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
    }

    /**
     * @return SdkConfigInterface
     */
    public static function makeConfigurator(): SdkConfigInterface
    {
        return new SdkConfig(new SdkFactory());
    }
}
