<?php

declare(strict_types=1);

namespace TrueLayer\Factories;

use TrueLayer\Constants\Endpoints;
use TrueLayer\Exceptions\MissingHttpImplementationException;
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
use TrueLayer\Traits\HttpClient;

class WebhookFactory implements WebhookVerifierFactoryInterface
{
    use HttpClient;

    /**
     * @param ConfigInterface $config
     *
     * @throws MissingHttpImplementationException
     *
     * @return WebhookInterface
     */
    public function make(ConfigInterface $config): WebhookInterface
    {
        $entityFactory = new EntityFactory($config);

        $jwksManager = $this->makeJwks($config);
        $verifier = new WebhookVerifier($jwksManager);

        $handlerManager = new WebhookHandlerManager();

        return new Webhook($verifier, $entityFactory, $handlerManager);
    }

    /**
     * @param ConfigInterface $config
     *
     * @throws MissingHttpImplementationException
     *
     * @return JwksManagerInterface
     */
    private function makeJwks(ConfigInterface $config): JwksManagerInterface
    {
        $webhooksBaseUri = $config->shouldUseProduction()
            ? Endpoints::WEBHOOKS_PROD_URL
            : Endpoints::WEBHOOKS_SANDBOX_URL;

        $jwksClient = new ApiClient(
            $this->discoverHttpClient($config),
            $this->discoverHttpRequestFactory($config),
            $webhooksBaseUri
        );

        $jwksClient = new Decorators\ExponentialBackoffDecorator($jwksClient);

        return new JwksManager($jwksClient, $config->getCache());
    }

    /**
     * @return WebhookConfigInterface
     */
    public static function makeConfigurator(): WebhookConfigInterface
    {
        return new WebhookConfig(new WebhookFactory());
    }
}
