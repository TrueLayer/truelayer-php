<?php

declare(strict_types=1);

namespace TrueLayer\Services\Webhooks;

use TrueLayer\Interfaces\Configuration\WebhookVerifierConfigInterface;
use TrueLayer\Interfaces\Webhooks\WebhookVerifierFactoryInterface;
use TrueLayer\Interfaces\Webhooks\WebhookVerifierInterface;
use TrueLayer\Services\Configuration\Config;

class WebhookVerifierConfig extends Config implements WebhookVerifierConfigInterface
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
     * @return WebhookVerifierInterface
     */
    public function create(): WebhookVerifierInterface
    {
        return $this->factory->make($this);
    }
}
