<?php

namespace TrueLayer\Contracts\Api;

use Closure;
use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiRequestValidationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\ApiResponseValidationException;

interface ApiRequestInterface
{
    /**
     * @param string $uri
     *
     * @return ApiRequestInterface
     */
    public function uri(string $uri): ApiRequestInterface;

    /**
     * @return string
     */
    public function getUri(): string;

    /**
     * @return string
     */
    public function getMethod(): string;

    /**
     * @param array $payload
     *
     * @return ApiRequestInterface
     */
    public function payload(array $payload): ApiRequestInterface;

    /**
     * @return array
     */
    public function getPayload(): array;

    /**
     * @return string
     * @throws ApiRequestJsonSerializationException
     */
    public function getJsonPayload(): string;

    /**
     * @param $key
     * @param $value
     * @return ApiRequestInterface
     */
    public function addHeader($key, $value): ApiRequestInterface;

    /**
     * @return array
     */
    public function getHeaders(): array;

    /**
     * @param callable $factory
     *
     * @return ApiRequestInterface
     */
    public function requestRules(callable $factory): ApiRequestInterface;

    /**
     * @return Closure
     */
    public function getRequestRules(): ?Closure;

    /**
     * @param callable $factory
     *
     * @return ApiRequestInterface
     */
    public function responseRules(callable $factory): ApiRequestInterface;

    /**
     * @return Closure
     */
    public function getResponseRules(): ?Closure;

    /**
     * @throws ApiRequestJsonSerializationException
     * @throws ApiRequestValidationException
     * @throws ApiResponseUnsuccessfulException
     * @throws ApiResponseValidationException
     *
     * @return array
     */
    public function post(): array;

    /**
     * @throws ApiRequestJsonSerializationException
     * @throws ApiRequestValidationException
     * @throws ApiResponseUnsuccessfulException
     * @throws ApiResponseValidationException
     *
     * @return array
     */
    public function get(): array;
}
