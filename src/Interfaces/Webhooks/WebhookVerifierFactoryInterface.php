<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Webhooks;

use TrueLayer\Interfaces\Configuration\WebhookVerifierConfigInterface;

interface WebhookVerifierFactoryInterface
{
    /**
     * @param WebhookVerifierConfigInterface $config
     *
     * @return WebhookVerifierInterface
     */
    public function make(WebhookVerifierConfigInterface $config): WebhookVerifierInterface;
}
