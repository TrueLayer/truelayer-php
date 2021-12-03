<?php

declare(strict_types=1);

namespace TrueLayer;

use GuzzleHttp\Client;
use TrueLayer\Builders\BeneficiaryBuilder;
use TrueLayer\Builders\SDKBuilder;
use TrueLayer\Constants\Endpoints;
use TrueLayer\Contracts\Api\ApiClientInterface;
use TrueLayer\Contracts\Builders\BeneficiaryBuilderInterface;
use TrueLayer\Contracts\Builders\SDKBuilderInterface;
use TrueLayer\Contracts\Models\PaymentCreatedInterface;
use TrueLayer\Contracts\Models\PaymentInterface;
use TrueLayer\Contracts\Models\UserInterface;
use TrueLayer\Contracts\SDKInterface;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Models\Payment;
use TrueLayer\Models\User;

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
     * @param array $data
     *
     * @return UserInterface
     */
    public function user(array $data = []): UserInterface
    {
        return User::fromArray($data);
    }

    /**
     * @return BeneficiaryBuilderInterface
     */
    public function beneficiary(): BeneficiaryBuilderInterface
    {
        return new BeneficiaryBuilder();
    }

    /**
     * @param array $data
     *
     * @return PaymentInterface
     */
    public function payment(array $data = []): PaymentInterface
    {
        return Payment::fromArray($data);
    }

    /**
     * @param PaymentInterface $payment
     *
     * @throws Exceptions\ApiRequestJsonSerializationException
     * @throws Exceptions\ApiRequestValidationException
     * @throws Exceptions\ApiResponseUnsuccessfulException
     * @throws Exceptions\ApiResponseValidationException
     *
     * @return PaymentCreatedInterface
     */
    public function createPayment(PaymentInterface $payment): PaymentCreatedInterface
    {
        return (new Api\Handlers\PaymentCreate())->execute($this->apiClient, $payment);
    }

    /**
     * @param string $id
     *
     * @throws Exceptions\ApiRequestJsonSerializationException
     * @throws Exceptions\ApiRequestValidationException
     * @throws Exceptions\ApiResponseUnsuccessfulException
     * @throws Exceptions\ApiResponseValidationException
     * @throws InvalidArgumentException
     *
     * @return PaymentInterface
     */
    public function getPayment(string $id): PaymentInterface
    {
        return (new Api\Handlers\PaymentRetrieve())->execute($this->apiClient, $id);
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
     * @return SDKInterface
     */
    public static function make(SDKBuilderInterface $builder): SDKInterface
    {
        $client = $builder->getHttpClient() ?: new Client();

        $signer = \TrueLayer\Signing\Signer::signWithPem(
            $builder->getKeyId(),
            $builder->getPem(),
            $builder->getPassphrase()
        );

        $filesystem = new \Illuminate\Filesystem\Filesystem();
        $loader = new \Illuminate\Translation\FileLoader($filesystem, \dirname(__FILE__, 2) . '/lang');
        $loader->addNamespace('lang', \dirname(__FILE__, 2) . '/lang');
        $loader->load('en', 'validation', 'lang');
        $translationFactory = new \Illuminate\Translation\Translator($loader, 'en');
        $validatorFactory = new \Illuminate\Validation\Factory($translationFactory);

        $authBaseUri = $builder->shouldUseProduction()
            ? Endpoints::AUTH_PROD_BASE
            : Endpoints::AUTH_SANDBOX_URL;

        $authApiClient = new Api\ApiClient($authBaseUri, $client, $signer, $validatorFactory);

        $authTokenRetrieve = new Api\Handlers\AuthTokenRetrieve(
            $authApiClient,
            $builder->getClientId(),
            $builder->getClientSecret()
        );

        $authTokenRetrieve->execute();

        $apiBaseUri = $builder->shouldUseProduction()
            ? Endpoints::API_PROD_URL
            : Endpoints::API_SANDBOX_URL;

        $apiClient = new Api\ApiClient($apiBaseUri, $client, $signer, $validatorFactory, $authTokenRetrieve);

        return new static($apiClient);
    }
}
