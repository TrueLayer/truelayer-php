<?php

declare(strict_types=1);

namespace TrueLayer\Services\ApiClient\Decorators;

use Composer\InstalledVersions;
use TrueLayer\Constants\CustomHeaders;
use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\SignerException;
use TrueLayer\Interfaces\ApiClient\ApiRequestInterface;

final class TLAgentDecorator extends BaseApiClientDecorator
{
    /**
     * @param ApiRequestInterface $apiRequest
     *
     * @throws ApiResponseUnsuccessfulException
     * @throws SignerException
     * @throws ApiRequestJsonSerializationException
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

        $apiRequest->header(CustomHeaders::TL_AGENT, "truelayer-php/{$version}");

        return $this->next->send($apiRequest);
    }
}
