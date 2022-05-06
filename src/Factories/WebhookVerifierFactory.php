<?php

namespace TrueLayer\Factories;

use Illuminate\Contracts\Validation\Factory as ValidatorFactory;
use Psr\Http\Client\ClientInterface as HttpClientInterface;
use TrueLayer\Interfaces\Configuration\WebhookVerifierConfigInterface;
use TrueLayer\Interfaces\Webhooks\WebhookVerifierFactoryInterface;
use TrueLayer\Interfaces\Webhooks\WebhookVerifierInterface;
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

    public function make(WebhookVerifierConfigInterface $config): WebhookVerifierInterface
    {
        $this->validatorFactory = $this->makeValidatorFactory();
        $this->makeHttpClient($config);

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

    /**
     * @return WebhookVerifierConfigInterface
     */
    public static function makeConfigurator(): WebhookVerifierConfigInterface
    {
        return new WebhookVerifierConfig(new WebhookVerifierFactory());
    }
}
