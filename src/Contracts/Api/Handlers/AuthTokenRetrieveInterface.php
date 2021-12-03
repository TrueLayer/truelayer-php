<?php

namespace TrueLayer\Contracts\Api\Handlers;

use TrueLayer\Contracts\Models\AuthTokenInterface;
use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiRequestValidationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\ApiResponseValidationException;

interface AuthTokenRetrieveInterface
{
    /**
     * @throws ApiRequestJsonSerializationException
     * @throws ApiRequestValidationException
     * @throws ApiResponseUnsuccessfulException
     * @throws ApiResponseValidationException
     *
     * @return AuthTokenInterface
     */
    public function execute(): AuthTokenInterface;
}
