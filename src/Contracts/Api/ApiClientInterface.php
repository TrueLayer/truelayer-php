<?php

namespace TrueLayer\Contracts\Api;

use Psr\Http\Message\RequestInterface;
use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiRequestValidationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\ApiResponseValidationException;
use TrueLayer\Exceptions\AuthTokenRetrievalFailure;

interface ApiClientInterface
{
    /**
     * @param string $uri
     * @param array $data
     * @param callable $requestRules
     * @param callable $responseRules
     * @return array
     */
    public function post(string $uri, array $data, callable $requestRules, callable $responseRules): array;

    /**
     * @param string $uri
     * @param callable $requestRules
     * @param callable $responseRules
     * @return array
     */
    public function get(string $uri, callable $requestRules, callable $responseRules): array;

    /**
     * @param string $method
     * @param string $uri
     * @param array $data
     * @param callable $requestRules
     * @param callable $responseRules
     * @return array
     */
    public function send(string $method, string $uri, array $data, callable $requestRules, callable $responseRules): array;
}
