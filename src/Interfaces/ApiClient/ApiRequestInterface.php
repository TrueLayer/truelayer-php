<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\ApiClient;

use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\SignerException;

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
     * @param mixed[] $payload
     *
     * @return ApiRequestInterface
     */
    public function payload(array $payload): ApiRequestInterface;

    /**
     * @return mixed[]
     */
    public function getPayload(): array;

    /**
     * @throws ApiRequestJsonSerializationException
     *
     * @return string
     */
    public function getJsonPayload(): string;

    /**
     * @param string $key
     * @param string $value
     *
     * @return ApiRequestInterface
     */
    public function header(string $key, string $value): ApiRequestInterface;

    /**
     * @return array<string, string>
     */
    public function getHeaders(): array;

    /**
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     * @throws SignerException
     *
     * @return mixed
     */
    public function post();

    /**
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     * @throws SignerException
     *
     * @return mixed
     */
    public function get();

    /**
     * @return bool
     */
    public function modifiesResources(): bool;
}
