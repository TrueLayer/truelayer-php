<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Configuration;

use TrueLayer\Exceptions\SignerException;
use TrueLayer\Interfaces\Client\ClientInterface;

interface ClientConfigInterface extends ConfigInterface
{
    /**
     * @return string
     */
    public function getClientId(): string;

    /**
     * @param string $clientId
     *
     * @return $this
     */
    public function clientId(string $clientId): self;

    /**
     * @return string
     */
    public function getClientSecret(): string;

    /**
     * @param string $clientSecret
     *
     * @return $this
     */
    public function clientSecret(string $clientSecret): self;

    /**
     * @return string
     */
    public function getKeyId(): string;

    /**
     * @param string $keyId
     *
     * @return $this
     */
    public function keyId(string $keyId): self;

    /**
     * @return string
     */
    public function getPem(): string;

    /**
     * @param string $pem
     *
     * @return $this
     */
    public function pem(string $pem): self;

    /**
     * @param string $pemBase64
     *
     * @throws SignerException
     *
     * @return $this
     */
    public function pemBase64(string $pemBase64): self;

    /**
     * @param string $path
     *
     * @throws SignerException
     *
     * @return $this
     */
    public function pemFile(string $path): self;

    /**
     * @param string $passphrase
     *
     * @return $this
     */
    public function passphrase(string $passphrase): self;

    /**
     * @return string|null
     */
    public function getPassphrase(): ?string;

    /**
     * @param string ...$scopes
     *
     * @return $this
     */
    public function scopes(string ...$scopes): self;

    /**
     * @return string[]
     */
    public function getScopes(): array;

    /**
     * @throws SignerException
     *
     * @return ClientInterface
     */
    public function create(): ClientInterface;
}
