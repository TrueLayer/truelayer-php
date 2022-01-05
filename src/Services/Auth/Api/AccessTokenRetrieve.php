<?php

declare(strict_types=1);

namespace TrueLayer\Services\Auth\Api;

use TrueLayer\Constants\Endpoints;
use TrueLayer\Contracts\Api\ApiClientInterface;
use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiRequestValidationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\ApiResponseValidationException;

final class AccessTokenRetrieve
{
    protected ApiClientInterface $api;

    public function __construct(ApiClientInterface $api)
    {
        $this->api = $api;
    }

    /**
     * @param string   $clientId
     * @param string   $clientSecret
     * @param string[] $scopes
     *
     * @throws ApiRequestJsonSerializationException
     * @throws ApiRequestValidationException
     * @throws ApiResponseUnsuccessfulException
     * @throws ApiResponseValidationException
     *
     * @return array
     */
    public function execute(string $clientId, string $clientSecret, array $scopes): array
    {
        return $this->api->request()
            ->uri(Endpoints::TOKEN)
            ->payload([
                'grant_type' => 'client_credentials',
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'scope' => implode(',', $scopes),
            ])
            ->responseRules(fn ($data) => [
                'access_token' => 'required|string',
                'expires_in' => 'required|int',
            ])
            ->post();
    }
}
