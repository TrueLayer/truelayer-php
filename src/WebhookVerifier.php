<?php

declare(strict_types=1);

namespace TrueLayer;

use TrueLayer\Factories\WebhookVerifierFactory;
use TrueLayer\Interfaces\Configuration\WebhookVerifierConfigInterface;

final class WebhookVerifier
{
    /**
     * @return WebhookVerifierConfigInterface
     */
    public static function configure(): WebhookVerifierConfigInterface
    {
        return WebhookVerifierFactory::makeConfigurator();
    }
}
