<?php

namespace TrueLayer\Contracts\Auth;

use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiRequestValidationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\ApiResponseValidationException;

interface AccessTokenInterface
{
    /**
     * @throws ApiRequestJsonSerializationException
     * @throws ApiRequestValidationException
     * @throws ApiResponseUnsuccessfulException
     * @throws ApiResponseValidationException
     *
     * @return string
     */
    public function getAccessToken(): string;

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
