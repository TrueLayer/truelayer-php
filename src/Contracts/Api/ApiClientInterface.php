<?php

declare(strict_types=1);

namespace TrueLayer\Contracts\Api;

use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;

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
     * @throws ApiResponseUnsuccessfulException
     *
     * @return mixed
     */
    public function send(ApiRequestInterface $apiRequest);
}
