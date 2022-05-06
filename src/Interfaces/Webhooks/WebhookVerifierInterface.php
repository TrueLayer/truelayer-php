<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Webhooks;

use TrueLayer\Signing\Contracts\Verifier as VerifierInterface;

interface WebhookVerifierInterface
{
    /**
     * @return VerifierInterface
     */
    public function verifier(): VerifierInterface;
}
