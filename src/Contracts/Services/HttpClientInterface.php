<?php

namespace TrueLayer\Contracts\Services;

use Psr\Http\Message\ResponseInterface;
use TrueLayer\Signing\Contracts\Signer;

interface HttpClientInterface
{
    /**
     * @param Signer $signer
     *
     * @return HttpClientInterface
     */
    public function signer(Signer $signer): HttpClientInterface;

    /**
     * @param bool $withSignature
     *
     * @return HttpClientInterface
     */
    public function withSignature(bool $withSignature = true): HttpClientInterface;

    /**
     * @param AuthTokenManagerInterface $authTokenManager
     *
     * @return HttpClientInterface
     */
    public function authTokenManager(AuthTokenManagerInterface $authTokenManager): HttpClientInterface;

    /**
     * @param bool $withAuthToken
     *
     * @return HttpClientInterface
     */
    public function withAuthToken(bool $withAuthToken = true): HttpClientInterface;

    /**
     * @param $uri
     * @param array $data
     * @param array $headers
     * @return ResponseInterface
     */
    public function get($uri, array $data = [], array $headers = []): ResponseInterface;

    /**
     * @param $uri
     * @param array $data
     * @param array $headers
     * @return ResponseInterface
     */
    public function post($uri, array $data = [], array $headers = []): ResponseInterface;
}
