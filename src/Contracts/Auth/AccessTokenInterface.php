<?php

declare(strict_types=1);

namespace TrueLayer\Contracts\Auth;

use TrueLayer\Contracts\HasAttributesInterface;
use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;

interface AccessTokenInterface
{
    /**
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     *
     * @return string|null
     */
    public function getAccessToken(): ?string;

    /**
     * @return int|null
     */
    public function getRetrievedAt(): ?int;

    /**
     * @return int|null
     */
    public function getExpiresIn(): ?int;

    /**
     * @param int $safetyMargin
     *
     * @return bool
     */
    public function isExpired(int $safetyMargin = 10): bool;

    /**
     * @return AccessTokenInterface
     */
    public function clear(): AccessTokenInterface;
}
