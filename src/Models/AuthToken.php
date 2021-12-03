<?php

declare(strict_types=1);

namespace TrueLayer\Models;

use TrueLayer\Contracts\Models\AuthTokenInterface;

class AuthToken implements AuthTokenInterface
{
    /**
     * @var string|null
     */
    private ?string $accessToken = null;

    /**
     * @var int|null
     */
    private ?int $retrievedAt = null;

    /**
     * @var int|null
     */
    private ?int $expiresIn = null;

    /**
     * @return string|null
     */
    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    /**
     * @param string $token
     * @return AuthTokenInterface
     */
    public function accessToken(string $token): AuthTokenInterface
    {
        $this->accessToken = $token;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getRetrievedAt(): ?int
    {
        return $this->retrievedAt;
    }

    /**
     * @param int $retrievedAt
     * @return AuthTokenInterface
     */
    public function retrievedAt(int $retrievedAt): AuthTokenInterface
    {
        $this->retrievedAt = $retrievedAt;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getExpiresIn(): ?int
    {
        return $this->expiresIn;
    }

    /**
     * @param int $expiresIn
     * @return AuthTokenInterface
     */
    public function expiresIn(int $expiresIn): AuthTokenInterface
    {
        $this->expiresIn = $expiresIn;
        return $this;
    }

    /**
     * @param int $safetyMargin
     * @return bool
     */
    public function isExpired(int $safetyMargin = 10): bool
    {
        return $this->retrievedAt + $this->expiresIn - $safetyMargin <= \time();
    }

    /**
     * @param array $data
     * @return AuthTokenInterface
     */
    public static function fromArray(array $data): AuthTokenInterface
    {
        return (new static)
            ->accessToken($data['access_token'] ?? null)
            ->retrievedAt($data['retrieved_at'] ?? null)
            ->expiresIn($data['expires_in'] ?? null);
    }
}
