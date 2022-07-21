<?php

declare(strict_types=1);

namespace TrueLayer;

use TrueLayer\Factories\WebhookFactory;
use TrueLayer\Interfaces\Configuration\WebhookConfigInterface;

final class Webhook
{
    /**
     * @return WebhookConfigInterface
     */
    public static function configure(): WebhookConfigInterface
    {
        return WebhookFactory::makeConfigurator();
    }
}
