<?php

declare(strict_types=1);

namespace TrueLayer\Services\Auth\Api;

use TrueLayer\Constants\Endpoints;
use TrueLayer\Contracts\Api\ApiClientInterface;
use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiRequestValidationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\ApiResponseValidationException;

class AuthTokenRetrieve
{
    /**
     * @param ApiClientInterface $api
     * @param string             $clientId
     * @param string             $clientSecret
     *
     * @throws ApiRequestJsonSerializationException
     * @throws ApiRequestValidationException
     * @throws ApiResponseUnsuccessfulException
     * @throws ApiResponseValidationException
     *
     * @return array
     */
    public function execute(ApiClientInterface $api, string $clientId, string $clientSecret): array
    {
        return $api->request()
            ->uri(Endpoints::TOKEN)
            ->payload([
                'grant_type' => 'client_credentials',
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'scope' => 'payments',
            ])
            ->responseRules(fn ($data) => [
                'access_token' => 'required|string',
                'expires_in' => 'required|int',
            ])
            ->post();
    }
}
