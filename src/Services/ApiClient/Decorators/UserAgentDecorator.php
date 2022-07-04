<?php

declare(strict_types=1);

namespace TrueLayer\Services\ApiClient\Decorators;

use Composer\InstalledVersions;
use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\SignerException;
use TrueLayer\Interfaces\ApiClient\ApiRequestInterface;

final class UserAgentDecorator extends BaseApiClientDecorator
{
    /**
     * @param ApiRequestInterface $apiRequest
     *
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     * @throws SignerException
     *
     * @return mixed
     */
    public function send(ApiRequestInterface $apiRequest)
    {
        try {
            $version = InstalledVersions::getPrettyVersion('truelayer/client');
        } catch (\OutOfBoundsException $e) {
            $version = 'unknown';
        }

        $apiRequest->header('User-Agent', "truelayer-php/{$version}");

        return $this->next->send($apiRequest);
    }
}
