<?php

declare(strict_types=1);

namespace TrueLayer;

use GuzzleHttp\Client;
use TrueLayer\Api\ApiClient;
use TrueLayer\Api\PaymentCreate;
use TrueLayer\Builders\BeneficiaryBuilder;
use TrueLayer\Builders\PaymentRequestBuilder;
use TrueLayer\Builders\SDKBuilder;
use TrueLayer\Constants\Endpoints;
use TrueLayer\Contracts\Api\ApiClientInterface;
use TrueLayer\Contracts\Builders\BeneficiaryBuilderInterface;
use TrueLayer\Contracts\Builders\PaymentRequestBuilderInterface;
use TrueLayer\Contracts\Builders\PayoutRequestBuilderInterface;
use TrueLayer\Contracts\Builders\SDKBuilderInterface;
use TrueLayer\Contracts\Models\UserInterface;
use TrueLayer\Contracts\SDKInterface;
use TrueLayer\Models\User;
use TrueLayer\Services\AuthTokenManager;
use TrueLayer\Services\HttpClient;

class SDK implements SDKInterface
{
    /**
     * @var ApiClientInterface
     */
    private ApiClientInterface $apiClient;

    /**
     * @param ApiClientInterface $apiClient
     */
    public function __construct(ApiClientInterface $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    /**
     * @return UserInterface
     */
    public function user(): UserInterface
    {
        return new User();
    }

    /**
     * @return BeneficiaryBuilderInterface
     */
    public function beneficiary(): BeneficiaryBuilderInterface
    {
        return new BeneficiaryBuilder();
    }

    /**
     * @return PaymentRequestBuilderInterface
     */
    public function payment(): PaymentRequestBuilderInterface
    {
        return new PaymentRequestBuilder(
            new PaymentCreate($this->apiClient)
        );
    }

    /**
     * @return PayoutRequestBuilderInterface
     */
    public function payout(): PayoutRequestBuilderInterface
    {
    }

    /**
     * @return SDKBuilderInterface
     */
    public static function configure(): SDKBuilderInterface
    {
        return new SDKBuilder();
    }

    /**
     * @param SDKBuilderInterface $builder
     *
     * @throws Exceptions\AuthTokenRetrievalFailure
     *
     * @return SDKInterface
     */
    public static function make(SDKBuilderInterface $builder): SDKInterface
    {
        $client = $builder->getHttpClient() ?: new Client();

        $authBaseUri = $builder->shouldUseProduction()
            ? Endpoints::AUTH_PROD_BASE
            : Endpoints::AUTH_SANDBOX_URL;

        $authTokenManager = new AuthTokenManager(
            new HttpClient($client, $authBaseUri),
            $builder->getClientId(),
            $builder->getClientSecret()
        );

        $authTokenManager->fetchAuthToken();

        $signer = \TrueLayer\Signing\Signer::signWithPem(
            $builder->getKeyId(),
            $builder->getPem()
        );

        $apiBaseUri = $builder->shouldUseProduction()
            ? Endpoints::API_PROD_URL
            : Endpoints::API_SANDBOX_URL;

        $filesystem = new \Illuminate\Filesystem\Filesystem();
        $loader = new \Illuminate\Translation\FileLoader($filesystem, dirname(__FILE__, 2) . '/lang');
        $loader->addNamespace('lang', dirname(__FILE__, 2) . '/lang');
        $loader->load('en', 'validation', 'lang');
        $translationFactory = new \Illuminate\Translation\Translator($loader, 'en');
        $validatorFactory = new \Illuminate\Validation\Factory($translationFactory);

        $apiClient = new ApiClient($client, $authTokenManager, $signer, $validatorFactory, $apiBaseUri);

        return new static($apiClient);
    }
}
