<?php

declare(strict_types=1);

namespace TrueLayer\Services\Api;

use TrueLayer\Constants\Endpoints;
use TrueLayer\Contracts\ApiClient\ApiClientInterface;
use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\SignerException;

final class AccessTokenApi
{
    private ApiClientInterface $api;

    public function __construct(ApiClientInterface $api)
    {
        $this->api = $api;
    }

    /**
     * @param string   $clientId
     * @param string   $clientSecret
     * @param string[] $scopes
     *
     *@throws ApiResponseUnsuccessfulException
     * @throws SignerException
     * @throws ApiRequestJsonSerializationException
     *
     * @return mixed[]
     */
    public function fetch(string $clientId, string $clientSecret, array $scopes): array
    {
        return (array) $this->api->request()
            ->uri(Endpoints::TOKEN)
            ->payload([
                'grant_type' => 'client_credentials',
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'scope' => \implode(' ', $scopes),
            ])
            ->post();
    }
}
