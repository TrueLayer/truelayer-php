<?php

declare(strict_types=1);

namespace TrueLayer\Factories;

use Illuminate\Contracts\Validation\Factory as ValidatorFactory;
use Psr\Http\Client\ClientInterface as HttpClientInterface;
use TrueLayer\Constants\Endpoints;
use TrueLayer\Interfaces\Configuration\WebhookVerifierConfigInterface;
use TrueLayer\Interfaces\Webhooks\JwksInterface;
use TrueLayer\Interfaces\Webhooks\WebhookVerifierFactoryInterface;
use TrueLayer\Interfaces\Webhooks\WebhookVerifierInterface;
use TrueLayer\Services\ApiClient\ApiClient;
use TrueLayer\Services\ApiClient\Decorators;
use TrueLayer\Services\Webhooks\Jwks;
use TrueLayer\Services\Webhooks\WebhookVerifier;
use TrueLayer\Services\Webhooks\WebhookVerifierConfig;
use TrueLayer\Traits\MakeValidatorFactory;

class WebhookVerifierFactory implements WebhookVerifierFactoryInterface
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

    private JwksInterface $jwks;

    public function make(WebhookVerifierConfigInterface $config): WebhookVerifierInterface
    {
        $this->validatorFactory = $this->makeValidatorFactory();
        $this->makeHttpClient($config);
        $this->makeJwks($config);

        return new WebhookVerifier();
    }

    /**
     * Build the HTTP client.
     *
     * @param WebhookVerifierConfigInterface $config
     */
    private function makeHttpClient(WebhookVerifierConfigInterface $config): void
    {
        $this->httpClient = $config->getHttpClient();
    }

    private function makeJwks(WebhookVerifierConfigInterface $config): void
    {
        $webhooksBaseUri = $config->shouldUseProduction()
            ? Endpoints::WEBHOOKS_PROD_URL
            : Endpoints::WEBHOOKS_SANDBOX_URL;

        $jwksClient = new ApiClient($this->httpClient, $webhooksBaseUri);
        $jwksClient = new Decorators\ExponentialBackoffDecorator($jwksClient);

        $this->jwks = new Jwks($jwksClient, $config->getCache(), $this->validatorFactory);
    }

    /**
     * @return WebhookVerifierConfigInterface
     */
    public static function makeConfigurator(): WebhookVerifierConfigInterface
    {
        return new WebhookVerifierConfig(new WebhookVerifierFactory());
    }
}
