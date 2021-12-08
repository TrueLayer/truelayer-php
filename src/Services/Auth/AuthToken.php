<?php

declare(strict_types=1);

namespace TrueLayer\Services\Auth;

use TrueLayer\Contracts\Api\ApiClientInterface;
use TrueLayer\Contracts\Auth\AuthTokenInterface;
use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiRequestValidationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\ApiResponseValidationException;
use TrueLayer\Services\Auth\Api\AuthTokenRetrieve;
use TrueLayer\Traits\HasAttributes;

class AuthToken implements AuthTokenInterface
{
    use HasAttributes;

    /**
     * @var ApiClientInterface
     */
    private ApiClientInterface $api;

    /**
     * @var string
     */
    private string $clientId;

    /**
     * @var string
     */
    private string $clientSecret;

    /**
     * @param ApiClientInterface $api
     * @param string             $clientId
     * @param string             $clientSecret
     */
    public function __construct(ApiClientInterface $api, string $clientId, string $clientSecret)
    {
        $this->api = $api;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
    }

    /**
     * @throws ApiRequestJsonSerializationException
     * @throws ApiRequestValidationException
     * @throws ApiResponseUnsuccessfulException
     * @throws ApiResponseValidationException
     *
     * @return string
     */
    public function getAccessToken(): string
    {
        if (!$this->get('access_token') || $this->isExpired()) {
            $this->retrieve();
        }

        return $this->get('access_token');
    }

    /**
     * @return int|null
     */
    public function getRetrievedAt(): ?int
    {
        return $this->get('retrieved_at');
    }

    /**
     * @return int|null
     */
    public function getExpiresIn(): ?int
    {
        return $this->get('expires_in');
    }

    /**
     * @param int $safetyMargin
     *
     * @return bool
     */
    public function isExpired(int $safetyMargin = 10): bool
    {
        return $this->getRetrievedAt() + $this->getExpiresIn() - $safetyMargin <= \time();
    }

    /**
     * @throws ApiRequestJsonSerializationException
     * @throws ApiRequestValidationException
     * @throws ApiResponseUnsuccessfulException
     * @throws ApiResponseValidationException
     */
    private function retrieve(): void
    {
        $data = (new AuthTokenRetrieve())->execute($this->api, $this->clientId, $this->clientSecret);
        $this->fill($data);
        $this->set('retrieved_at', \time());
    }
}
