<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Webhook;

use TrueLayer\Exceptions\WebhookVerificationFailedException;

interface WebhookVerifierInterface
{
    /**
     * @param string $path
     * @param array $headers
     * @param string $body
     * @throws WebhookVerificationFailedException
     */
    public function verify(string $path, array $headers, string $body): void;
}
