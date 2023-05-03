<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Webhook;

use TrueLayer\Exceptions\MissingHttpImplementationException;
use TrueLayer\Interfaces\Configuration\WebhookConfigInterface;

interface WebhookVerifierFactoryInterface
{
    /**
     * @param WebhookConfigInterface $config
     *
     * @throws MissingHttpImplementationException
     *
     * @return WebhookInterface
     */
    public function make(WebhookConfigInterface $config): WebhookInterface;
}
