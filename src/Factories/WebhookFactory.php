<?php

declare(strict_types=1);

namespace TrueLayer\Factories;

use Illuminate\Contracts\Validation\Factory as ValidatorFactory;
use Psr\Http\Client\ClientInterface as HttpClientInterface;
use TrueLayer\Constants\Endpoints;
use TrueLayer\Interfaces\Configuration\ConfigInterface;
use TrueLayer\Interfaces\Configuration\WebhookConfigInterface;
use TrueLayer\Interfaces\Webhook\JwksManagerInterface;
use TrueLayer\Interfaces\Webhook\WebhookInterface;
use TrueLayer\Interfaces\Webhook\WebhookVerifierFactoryInterface;
use TrueLayer\Services\ApiClient\ApiClient;
use TrueLayer\Services\ApiClient\Decorators;
use TrueLayer\Services\Webhooks\JwksManager;
use TrueLayer\Services\Webhooks\Webhook;
use TrueLayer\Services\Webhooks\WebhookConfig;
use TrueLayer\Services\Webhooks\WebhookHandlerManager;
use TrueLayer\Services\Webhooks\WebhookVerifier;
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

    private JwksManagerInterface $jwks;

    /**
     * @param ConfigInterface $config
     * @return WebhookInterface
     */
    public function make(ConfigInterface $config): WebhookInterface
    {
        $validatorFactory = $this->makeValidatorFactory();
        $entityFactory = new EntityFactory($validatorFactory, $config);

        $jwksManager = $this->makeJwks($config, $validatorFactory);
        $verifier = new WebhookVerifier($jwksManager);

        $handlerManager = new WebhookHandlerManager();

        return new Webhook($verifier, $entityFactory, $handlerManager);
    }

    /**
     * @param ConfigInterface $config
     * @param ValidatorFactory $validatorFactory
     * @return JwksManagerInterface
     */
    private function makeJwks(ConfigInterface $config, ValidatorFactory $validatorFactory): JwksManagerInterface
    {
        $webhooksBaseUri = $config->shouldUseProduction()
            ? Endpoints::WEBHOOKS_PROD_URL
            : Endpoints::WEBHOOKS_SANDBOX_URL;

        $jwksClient = new ApiClient($config->getHttpClient(), $webhooksBaseUri);
        $jwksClient = new Decorators\ExponentialBackoffDecorator($jwksClient);

        return new JwksManager($jwksClient, $config->getCache(), $validatorFactory);
    }

    /**
     * @return WebhookConfigInterface
     */
    public static function makeConfigurator(): WebhookConfigInterface
    {
        return new WebhookConfig(new WebhookFactory());
    }
}
