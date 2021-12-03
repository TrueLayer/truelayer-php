<?php

namespace TrueLayer\Contracts\Api;

use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiRequestValidationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\ApiResponseValidationException;

interface ApiRequestInterface
{
    /**
     * @param string $uri
     * @return ApiRequestInterface
     */
    public function uri(string $uri): ApiRequestInterface;

    /**
     * @param array $payload
     * @return ApiRequestInterface
     */
    public function payload(array $payload): ApiRequestInterface;

    /**
     * @param callable $factory
     * @return ApiRequestInterface
     */
    public function requestRules(callable $factory): ApiRequestInterface;

    /**
     * @param callable $factory
     * @return ApiRequestInterface
     */
    public function responseRules(callable $factory): ApiRequestInterface;

    /**
     * @return array
     * @throws ApiRequestJsonSerializationException
     * @throws ApiRequestValidationException
     * @throws ApiResponseUnsuccessfulException
     * @throws ApiResponseValidationException
     */
    public function post(): array;

    /**
     * @return array
     * @throws ApiRequestJsonSerializationException
     * @throws ApiRequestValidationException
     * @throws ApiResponseUnsuccessfulException
     * @throws ApiResponseValidationException
     */
    public function get(): array;
}
