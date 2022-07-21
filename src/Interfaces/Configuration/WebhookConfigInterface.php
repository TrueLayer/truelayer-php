<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Configuration;

use TrueLayer\Exceptions\SignerException;
use TrueLayer\Interfaces\Webhook\WebhookInterface;

interface WebhookConfigInterface extends ConfigInterface
{
    /**
     * @throws SignerException
     *
     * @return WebhookInterface
     */
    public function create(): WebhookInterface;
}
