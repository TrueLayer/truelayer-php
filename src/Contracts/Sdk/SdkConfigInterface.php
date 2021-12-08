<?php

namespace TrueLayer\Contracts\Sdk;

use Psr\Http\Client\ClientInterface;
use TrueLayer\Exceptions\InvalidArgumentException;

interface SdkConfigInterface
{
    /**
     * @return string|null
     */
    public function getClientId(): ?string;

    /**
     * @param string $clientId
     *
     * @return $this
     */
    public function clientId(string $clientId): self;

    /**
     * @return string|null
     */
    public function getClientSecret(): ?string;

    /**
     * @param string $clientSecret
     *
     * @return $this
     */
    public function clientSecret(string $clientSecret): self;

    /**
     * @return string|null
     */
    public function getKeyId(): ?string;

    /**
     * @param string $keyId
     *
     * @return $this
     */
    public function keyId(string $keyId): self;

    /**
     * @return string|null
     */
    public function getPem(): ?string;

    /**
     * @param string $pem
     *
     * @return $this
     */
    public function pem(string $pem): self;

    /**
     * @param string $path
     *
     * @throws InvalidArgumentException
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
     * @return bool
     */
    public function shouldUseProduction(): bool;

    /**
     * @param bool $useProduction
     *
     * @return $this
     */
    public function useProduction(bool $useProduction): self;

    /**
     * @return ClientInterface|null
     */
    public function getHttpClient(): ?ClientInterface;

    /**
     * @param ClientInterface $client
     *
     * @return $this
     */
    public function httpClient(ClientInterface $client): self;

    /**
     * @throws \TrueLayer\Exceptions\ApiRequestJsonSerializationException
     * @throws \TrueLayer\Exceptions\ApiRequestValidationException
     * @throws \TrueLayer\Exceptions\ApiResponseUnsuccessfulException
     * @throws \TrueLayer\Exceptions\ApiResponseValidationException
     *
     * @return SdkInterface
     */
    public function create(): SdkInterface;
}
