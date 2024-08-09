<?php

declare(strict_types=1);

namespace TrueLayer\Services\ApiClient\Decorators;

use TrueLayer\Constants\ResponseStatusCodes;
use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Interfaces\ApiClient\ApiClientInterface;
use TrueLayer\Interfaces\ApiClient\ApiRequestInterface;
use TrueLayer\Interfaces\Auth\AccessTokenInterface;
use TrueLayer\Services\Util\Retry;

final class AccessTokenDecorator extends BaseApiClientDecorator
{
    public const MAX_RETRIES = 1;

    /**
     * @var AccessTokenInterface
     */
    private AccessTokenInterface $accessToken;

    /**
     * @param ApiClientInterface   $next
     * @param AccessTokenInterface $accessToken
     */
    public function __construct(ApiClientInterface $next, AccessTokenInterface $accessToken)
    {
        parent::__construct($next);
        $this->accessToken = $accessToken;
    }

    /**
     * @param ApiRequestInterface $apiRequest
     *
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     *
     * @return mixed
     */
    public function send(ApiRequestInterface $apiRequest)
    {
        return Retry::max(self::MAX_RETRIES)
            ->when(fn ($e) => ($e instanceof ApiResponseUnsuccessfulException)
                && $e->getStatusCode() === ResponseStatusCodes::UNAUTHORIZED
            )
            ->start(function (int $attempt) use ($apiRequest) {
                if ($attempt > 0) {
                    $this->accessToken->clear();
                }

                $apiRequest->header('Authorization', "Bearer {$this->accessToken->getAccessToken()}");

                return $this->next->send($apiRequest);
            });
    }
}
