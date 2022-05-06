<?php

declare(strict_types=1);

namespace TrueLayer\Services\Webhooks;

use TrueLayer\Interfaces\Webhooks\WebhookVerifierInterface;
use TrueLayer\Signing\Contracts\Verifier;

class WebhookVerifier implements WebhookVerifierInterface
{
    private Verifier $verifier;

    /**
     * @return Verifier
     */
    public function verifier(): Verifier
    {
        return $this->verifier;
    }
}
