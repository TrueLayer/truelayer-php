<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Webhook;

use TrueLayer\Interfaces\Configuration\WebhookConfigInterface;

interface WebhookVerifierFactoryInterface
{
    /**
     * @param WebhookVerifierConfigInterface $config
     *
     * @return WebhookInterface
     */
    public function make(WebhookConfigInterface $config): WebhookInterface;
}
