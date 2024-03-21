<?php

declare(strict_types=1);

namespace TrueLayer\Services\Webhooks;

use TrueLayer\Constants\CustomHeaders;
use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\SignerException;
use TrueLayer\Exceptions\WebhookHandlerInvalidArgumentException;
use TrueLayer\Exceptions\WebhookVerificationFailedException;
use TrueLayer\Interfaces\Webhook\JwksManagerInterface;
use TrueLayer\Interfaces\Webhook\WebhookVerifierInterface;
use TrueLayer\Signing\Exceptions\InvalidAlgorithmException;
use TrueLayer\Signing\Exceptions\InvalidArgumentException;
use TrueLayer\Signing\Exceptions\InvalidSignatureException;
use TrueLayer\Signing\Exceptions\InvalidTrueLayerSignatureVersionException;
use TrueLayer\Signing\Exceptions\RequestPathNotFoundException;
use TrueLayer\Signing\Exceptions\RequiredHeaderMissingException;
use TrueLayer\Signing\Verifier;

class WebhookVerifier implements WebhookVerifierInterface
{
    /**
     * @var JwksManagerInterface
     */
    private JwksManagerInterface $jwksManager;

    /**
     * @param JwksManagerInterface $jwksManager
     */
    public function __construct(JwksManagerInterface $jwksManager)
    {
        $this->jwksManager = $jwksManager;
    }

    /**
     * @param string $path
     * @param array<string, string> $headers
     * @param string $body
     *
     * @throws WebhookVerificationFailedException
     */
    public function verify(string $path, array $headers, string $body): void
    {
        // The verification process requires a path without the trailing slash
        $path = \rtrim($path, '/');

        try {
            $this->verifyWithRetry($path, $headers, $body);
        } catch (\Exception $e) {
            throw new WebhookVerificationFailedException('Webhook signature verification failed.', 0, $e);
        }
    }

    /**
     * If verification fails and a cache is in use, it may be because the
     * TL keys have changed. We re-fetch them and attempt to verify again.
     * A second verification fail is simply re-thrown.
     *
     * @param string $path
     * @param array<string, string> $headers
     * @param string $body
     *
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     * @throws InvalidAlgorithmException
     * @throws InvalidArgumentException
     * @throws InvalidSignatureException
     * @throws InvalidTrueLayerSignatureVersionException
     * @throws RequestPathNotFoundException
     * @throws RequiredHeaderMissingException
     * @throws SignerException
     * @throws WebhookHandlerInvalidArgumentException
     */
    private function verifyWithRetry(string $path, array $headers, string $body): void
    {
        try {
            $this->verifySignature($path, $headers, $body);
        } catch (InvalidSignatureException $e) {
            if ($this->jwksManager->hasCache()) {
                $this->jwksManager->retrieve();
                $this->verifySignature($path, $headers, $body);
            } else {
                throw $e;
            }
        }
    }

    /**
     * @param string $path
     * @param array<string, string> $headers
     * @param string $body
     *
     * @throws InvalidSignatureException
     * @throws WebhookHandlerInvalidArgumentException
     * @throws InvalidAlgorithmException
     * @throws InvalidArgumentException
     * @throws InvalidTrueLayerSignatureVersionException
     * @throws RequestPathNotFoundException
     * @throws RequiredHeaderMissingException
     */
    private function verifySignature(string $path, array $headers, string $body): void
    {
        $signatureHeader = \strtolower(CustomHeaders::SIGNATURE);
        if (empty($headers[$signatureHeader])) {
            throw new WebhookHandlerInvalidArgumentException("{$signatureHeader} header not provided.");
        }

        $verifier = Verifier::verifyWithJsonKeys(...$this->jwksManager->getJsonKeys()); // @phpstan-ignore-line
        $verifier->method('POST')
            ->path($path)
            ->body($body)
            ->headers($headers)
            ->verify($headers[$signatureHeader]);
    }
}
