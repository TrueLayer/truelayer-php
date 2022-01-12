<?php

declare(strict_types=1);

namespace TrueLayer\Contracts\ApiClient;

use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\SignerException;

interface ApiClientInterface
{
    /**
     * @return ApiRequestInterface
     */
    public function request(): ApiRequestInterface;

    /**
     * @param ApiRequestInterface $apiRequest
     *
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     * @throws SignerException
     *
     * @return mixed
     */
    public function send(ApiRequestInterface $apiRequest);
}
