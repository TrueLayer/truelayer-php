<?php

declare(strict_types=1);

namespace TrueLayer\Factories;

use Illuminate\Contracts\Validation\Factory as ValidatorFactory;
use Psr\Http\Client\ClientInterface as HttpClientInterface;
use TrueLayer\Constants\Endpoints;
use TrueLayer\Interfaces\Configuration\ConfigInterface;
use TrueLayer\Interfaces\Configuration\WebhookConfigInterface;
use TrueLayer\Interfaces\Webhook\JwksInterface;
use TrueLayer\Interfaces\Webhook\WebhookInterface;
use TrueLayer\Interfaces\Webhook\WebhookVerifierFactoryInterface;
use TrueLayer\Services\ApiClient\ApiClient;
use TrueLayer\Services\ApiClient\Decorators;
use TrueLayer\Services\Webhooks\Jwks;
use TrueLayer\Services\Webhooks\Webhook;
use TrueLayer\Services\Webhooks\WebhookConfig;
use TrueLayer\Signing\Exceptions\InvalidArgumentException;
use TrueLayer\Signing\Verifier;
use TrueLayer\Traits\MakeValidatorFactory;

class WebhookFactory implements WebhookVerifierFactoryInterface
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

    /**
     * @param WebhookConfigInterface $config
     * @return WebhookInterface
     * @throws InvalidArgumentException
     */
    public function make(ConfigInterface $config): WebhookInterface
    {
        $this->validatorFactory = $this->makeValidatorFactory();
        $this->makeHttpClient($config);
        $this->makeJwks($config);

        $verifier = Verifier::verifyWithJsonKeys(...$this->jwks->getJsonKeys());
        $entityFactory = new EntityFactory($this->validatorFactory, $config);

        return new Webhook($verifier, $entityFactory);
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
     * @param ConfigInterface $config
     */
    private function makeJwks(ConfigInterface $config): void
    {
        $webhooksBaseUri = $config->shouldUseProduction()
            ? Endpoints::WEBHOOKS_PROD_URL
            : Endpoints::WEBHOOKS_SANDBOX_URL;

        $jwksClient = new ApiClient($this->httpClient, $webhooksBaseUri);
        $jwksClient = new Decorators\ExponentialBackoffDecorator($jwksClient);

        $this->jwks = new Jwks($jwksClient, $config->getCache(), $this->validatorFactory);
    }

    /**
     * @return WebhookConfigInterface
     */
    public static function makeConfigurator(): WebhookConfigInterface
    {
        return new WebhookConfig(new WebhookFactory());
    }
}
