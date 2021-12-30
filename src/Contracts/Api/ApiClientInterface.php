<?php

declare(strict_types=1);

namespace TrueLayer\Contracts\Api;

use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiRequestValidationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\ApiResponseValidationException;

interface ApiClientInterface
{
    /**
     * @return ApiRequestInterface
     */
    public function request(): ApiRequestInterface;

    /**
     * @param ApiRequestInterface $apiRequest
     *
     * @throws ApiRequestJsonSerializationException
     * @throws ApiRequestValidationException
     * @throws ApiResponseUnsuccessfulException
     * @throws ApiResponseValidationException
     *
     * @return mixed
     */
    public function send(ApiRequestInterface $apiRequest);
}
