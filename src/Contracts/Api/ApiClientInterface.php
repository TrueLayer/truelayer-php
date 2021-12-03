<?php

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
     * @param string        $method
     * @param string        $uri
     * @param array         $data
     * @param callable|null $requestRules
     * @param callable|null $responseRules
     *
     * @throws ApiRequestJsonSerializationException
     * @throws ApiRequestValidationException
     * @throws ApiResponseUnsuccessfulException
     * @throws ApiResponseValidationException
     *
     * @return array
     */
    public function send(string $method, string $uri, array $data, callable $requestRules = null, callable $responseRules = null): array;
}
