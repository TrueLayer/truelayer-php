<?php

declare(strict_types=1);

namespace TrueLayer\Services\Webhooks;

use TrueLayer\Interfaces\Webhooks\JwksInterface;
use TrueLayer\Interfaces\Webhooks\WebhookVerifierInterface;
use TrueLayer\Signing\Contracts\Verifier as VerifierInterface;
use TrueLayer\Signing\Exceptions\InvalidArgumentException;
use TrueLayer\Signing\Verifier;

class WebhookVerifier implements WebhookVerifierInterface
{
    private VerifierInterface $verifier;

    private JwksInterface $jwks;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(JwksInterface $jwks)
    {
        $this->jwks = $jwks;
        $this->verifier = Verifier::verifyWithJsonKeys(...$jwks->getJsonKeys());
    }

    /**
     * @return VerifierInterface
     */
    public function verifier(): VerifierInterface
    {
        return $this->verifier;
    }
}
