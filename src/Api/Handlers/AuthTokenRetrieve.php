<?php

declare(strict_types=1);

namespace TrueLayer\Api\Handlers;

use TrueLayer\Constants\Endpoints;
use TrueLayer\Contracts\Api\ApiClientInterface;
use TrueLayer\Contracts\Api\Handlers\AuthTokenRetrieveInterface;
use TrueLayer\Contracts\Models\AuthTokenInterface;
use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiRequestValidationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\ApiResponseValidationException;
use TrueLayer\Models\AuthToken;

class AuthTokenRetrieve implements AuthTokenRetrieveInterface
{
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
     * @param string $clientId
     * @param string $clientSecret
     */
    public function __construct(ApiClientInterface $api, string $clientId, string $clientSecret)
    {
        $this->api = $api;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
    }

    /**
     * @return AuthTokenInterface
     * @throws ApiRequestJsonSerializationException
     * @throws ApiRequestValidationException
     * @throws ApiResponseUnsuccessfulException
     * @throws ApiResponseValidationException
     */
    public function execute(): AuthTokenInterface
    {
        $data = $this->api->request()
            ->uri(Endpoints::TOKEN)
            ->payload([
                'grant_type' => 'client_credentials',
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'scope' => 'payments',
            ])
            ->responseRules(fn ($data) => [
                'access_token' => 'required|string',
                'expires_in' => 'required|int'
            ])
            ->post();

        $data['retrieved_at'] = \time();

        return AuthToken::fromArray($data);
    }
}
