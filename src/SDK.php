<?php

declare(strict_types=1);

namespace TrueLayer;

use GuzzleHttp\Client;
use TrueLayer\Builders\BeneficiaryBuilder;
use TrueLayer\Builders\PaymentRequestBuilder;
use TrueLayer\Builders\SDKBuilder;
use TrueLayer\Constants\Endpoints;
use TrueLayer\Contracts\Builders\BeneficiaryBuilderInterface;
use TrueLayer\Contracts\Builders\PaymentRequestBuilderInterface;
use TrueLayer\Contracts\Builders\PayoutRequestBuilderInterface;
use TrueLayer\Contracts\Builders\SDKBuilderInterface;
use TrueLayer\Contracts\Models\UserInterface;
use TrueLayer\Contracts\SDKInterface;
use TrueLayer\Contracts\Services\HttpClientInterface;
use TrueLayer\Models\User;
use TrueLayer\Services\AuthTokenManager;
use TrueLayer\Services\HttpClient;

class SDK implements SDKInterface
{
    /**
     * @var HttpClientInterface
     */
    private HttpClientInterface $httpClient;

    /**
     * @param HttpClientInterface $httpClient
     */
    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
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
        return new PaymentRequestBuilder($this->httpClient);
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

        $httpClient = new HttpClient($client, $apiBaseUri);
        $httpClient
            ->authTokenManager($authTokenManager)
            ->signer($signer);

        return new static($httpClient);
    }
}
