<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Configuration;

use TrueLayer\Exceptions\SignerException;
use TrueLayer\Interfaces\Webhooks\WebhookVerifierInterface;

interface WebhookVerifierConfigInterface extends ConfigInterface
{
    /**
     * @throws SignerException
     *
     * @return WebhookVerifierInterface
     */
    public function create(): WebhookVerifierInterface;
}
