<?php

declare(strict_types=1);

namespace TrueLayer\Services\Api;

use TrueLayer\Constants\Endpoints;
use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\SignerException;
use TrueLayer\Interfaces\Api\ProvidersApiInterface;

final class ProvidersApi extends Api implements ProvidersApiInterface
{
    /**
     * @param mixed[] $searchRequest
     *
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     * @throws SignerException
     *
     * @return mixed[]
     */
    public function search(array $searchRequest): array
    {
        return (array) $this->request()
            ->uri(Endpoints::PROVIDERS_SEARCH)
            ->payload($searchRequest)
            ->post();
    }
}