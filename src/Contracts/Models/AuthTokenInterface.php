<?php

namespace TrueLayer\Contracts\Models;

use TrueLayer\Contracts\ArrayFactoryInterface;

interface AuthTokenInterface extends ArrayFactoryInterface
{
    /**
     * @return string|null
     */
    public function getAccessToken(): ?string;

    /**
     * @param string $token
     * @return AuthTokenInterface
     */
    public function accessToken(string $token): AuthTokenInterface;

    /**
     * @return int|null
     */
    public function getRetrievedAt(): ?int;

    /**
     * @param int $retrievedAt
     * @return AuthTokenInterface
     */
    public function retrievedAt(int $retrievedAt): AuthTokenInterface;

    /**
     * @return int|null
     */
    public function getExpiresIn(): ?int;

    /**
     * @param int $expiresIn
     * @return AuthTokenInterface
     */
    public function expiresIn(int $expiresIn): AuthTokenInterface;

    /**
     * @param int $safetyMargin
     * @return bool
     */
    public function isExpired(int $safetyMargin = 10): bool;
}
