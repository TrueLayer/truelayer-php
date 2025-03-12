<?php

declare(strict_types=1);

namespace TrueLayer\Services\ApiClient\Decorators;

use Composer\InstalledVersions;
use TrueLayer\Constants\CustomHeaders;
use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\SignerException;
use TrueLayer\Interfaces\ApiClient\ApiClientInterface;
use TrueLayer\Interfaces\ApiClient\ApiRequestInterface;

final class TLAgentDecorator extends BaseApiClientDecorator
{
    private string $headerValue = 'truelayer-php';

    /**
     * @param ApiClientInterface $next
     * @param array<string, string|bool> $meta
     */
    public function __construct(ApiClientInterface $next, array $meta = [])
    {
        parent::__construct($next);

        try {
            $clientVersion = InstalledVersions::getPrettyVersion('truelayer/client');

            $commentItems = array_merge(['php.version' => phpversion()], $meta);
            $comment = json_encode($commentItems);

            $this->headerValue .= " / {$clientVersion} {$comment}";
        } catch (\Exception) {
        }
    }

    /**
     * @param ApiRequestInterface $apiRequest
     *
     * @return mixed
     * @throws SignerException
     * @throws ApiRequestJsonSerializationException
     *
     * @throws ApiResponseUnsuccessfulException
     */
    public function send(ApiRequestInterface $apiRequest)
    {
        $apiRequest->header(CustomHeaders::TL_AGENT, $this->headerValue);

        return $this->next->send($apiRequest);
    }
}
