<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Configuration;

use TrueLayer\Exceptions\SignerException;
use TrueLayer\Interfaces\Webhook\WebhookInterface;

interface WebhookConfigInterface extends ConfigInterface
{
    /**
     * @return WebhookInterface
     * @throws SignerException
     *
     */
    public function create(): WebhookInterface;
}
