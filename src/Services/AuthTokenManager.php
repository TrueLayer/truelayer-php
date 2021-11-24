<?php

declare(strict_types=1);

namespace TrueLayer\Services;

use TrueLayer\Constants\Endpoints;
use TrueLayer\Contracts\Services\AuthTokenManagerInterface;
use TrueLayer\Contracts\Services\HttpClientInterface;
use TrueLayer\Exceptions\AuthTokenRetrievalFailure;

class AuthTokenManager implements AuthTokenManagerInterface
{
    /**
     * We apply a safety margin when checking for token expiration.
     * This is to prevent errors due to network delays.
     */
    const SAFETY_MARGIN = 10;

    /**
     * @var HttpClientInterface
     */
    private HttpClientInterface $httpClient;

    /**
     * @var string
     */
    private string $clientId;

    /**
     * @var string
     */
    private string $clientSecret;

    /**
     * @var string
     */
    private string $accessToken;

    /**
     * @var int
     */
    private int $retrievedAt;

    /**
     * @var int
     */
    private int $expiresIn;

    /**
     * @param HttpClientInterface $httpClient
     * @param string $clientId
     * @param string $clientSecret
     */
    public function __construct(HttpClientInterface $httpClient, string $clientId, string $clientSecret)
    {
        $this->httpClient = $httpClient;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
    }

    /**
     * @return string
     * @throws AuthTokenRetrievalFailure
     */
    public function getAccessToken(): string
    {
        if (empty($this->accessToken) || $this->isExpired()) {
            $this->fetchAuthToken();
        }

        return $this->accessToken;
    }

    /**
     * @return bool
     */
    private function isExpired(): bool
    {
        return $this->retrievedAt + $this->expiresIn - self::SAFETY_MARGIN <= \time();
    }

    /**
     * @throws AuthTokenRetrievalFailure
     */
    public function fetchAuthToken(): void
    {
        $response = $this->httpClient->post(Endpoints::TOKEN, [
            'grant_type' => 'client_credentials',
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'scope' => 'payments',
        ]);

        $data = $response->getBody()->getContents();
        $data = json_decode($data);

        if (empty($data->access_token) || empty($data->expires_in)) {
            throw new AuthTokenRetrievalFailure();
        }

        $this->accessToken = (string) $data->access_token;
        $this->expiresIn = (int) $data->expires_in;
        $this->retrievedAt = time();
    }
}
