<?php

declare(strict_types=1);

namespace TrueLayer\Services\Webhooks;

use TrueLayer\Exceptions\MissingHttpImplementationException;
use TrueLayer\Interfaces\Configuration\WebhookConfigInterface;
use TrueLayer\Interfaces\Webhook\WebhookInterface;
use TrueLayer\Interfaces\Webhook\WebhookVerifierFactoryInterface;
use TrueLayer\Services\Configuration\Config;

class WebhookConfig extends Config implements WebhookConfigInterface
{
    /**
     * @var WebhookVerifierFactoryInterface
     */
    private WebhookVerifierFactoryInterface $factory;

    /**
     * @param WebhookVerifierFactoryInterface $factory
     */
    public function __construct(WebhookVerifierFactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @throws MissingHttpImplementationException
     *
     * @return WebhookInterface
     */
    public function create(): WebhookInterface
    {
        return $this->factory->make($this);
    }
}
