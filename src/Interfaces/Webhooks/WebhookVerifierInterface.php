<?php

namespace TrueLayer\Interfaces\Webhooks;

use TrueLayer\Signing\Contracts\Verifier;

interface WebhookVerifierInterface
{
    public function verifier(): Verifier;
}
