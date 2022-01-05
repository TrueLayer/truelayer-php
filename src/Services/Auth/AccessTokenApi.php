<?php

declare(strict_types=1);

namespace TrueLayer\Services\Auth;

use TrueLayer\Constants\Endpoints;
use TrueLayer\Contracts\Api\ApiClientInterface;
use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;

final class AccessTokenApi
{
    /**
     * @param ApiClientInterface $api
     * @param string             $clientId
     * @param string             $clientSecret
     *
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     *
     * @return mixed[]
     */
    public function fetch(ApiClientInterface $api, string $clientId, string $clientSecret): array
    {
        return $api->request()
            ->uri(Endpoints::TOKEN)
            ->payload([
                'grant_type' => 'client_credentials',
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'scope' => 'payments',
            ])
            ->post();
    }
}
