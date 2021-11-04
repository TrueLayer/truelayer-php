<?php

declare(strict_types=1);

namespace TrueLayer;

class Options
{
    private string $clientId;
    private string $clientSecret;
    private string $kid;
    private string $privateKey;
    private bool $useSandbox;

    public function __construct(
        string $clientId,
        string $clientSecret,
        string $kid,
        string $privateKey,
        bool $useSandbox
    ) {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->kid = $kid;
        $this->privateKey = $privateKey;
        $this->useSandbox = $useSandbox;
    }

    public function getClientId(): string
    {
        return $this->clientId;
    }

    public function getClientSecret(): string
    {
        return $this->clientSecret;
    }

    public function getKid(): string
    {
        return $this->kid;
    }

    public function getPrivateKey(): string
    {
        return $this->privateKey;
    }

    public function useSandbox(): bool
    {
        return $this->useSandbox;
    }
}
