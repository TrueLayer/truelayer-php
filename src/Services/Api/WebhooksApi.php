<?php

namespace TrueLayer\Services\Api;

use TrueLayer\Constants\Endpoints;
use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\SignerException;
use TrueLayer\Interfaces\Api\WebhooksApiInterface;

class WebhooksApi extends Api implements WebhooksApiInterface
{
    /**
     * @throws SignerException
     * @throws ApiResponseUnsuccessfulException
     * @throws ApiRequestJsonSerializationException
     *
     * @return mixed[]
     */
    public function jwks(): array
    {
        return (array) $this->request()
            ->uri(Endpoints::JWKS)
            ->get();
    }
}
