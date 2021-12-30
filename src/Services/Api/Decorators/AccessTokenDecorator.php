<?php

declare(strict_types=1);

namespace TrueLayer\Services\Api\Decorators;

use TrueLayer\Constants\ResponseStatusCodes;
use TrueLayer\Contracts\Api\ApiClientInterface;
use TrueLayer\Contracts\Api\ApiRequestInterface;
use TrueLayer\Contracts\Auth\AccessTokenInterface;
use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiRequestValidationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\ApiResponseValidationException;
use TrueLayer\Services\Util\Retry;

class AccessTokenDecorator extends BaseApiClientDecorator
{
    public const MAX_RETRIES = 1;

    /**
     * @var AccessTokenInterface
     */
    private AccessTokenInterface $accessToken;

    /**
     * @param ApiClientInterface $next
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
     * @throws ApiRequestValidationException
     * @throws ApiResponseUnsuccessfulException
     * @throws ApiResponseValidationException
     *
     * @return mixed
     */
    public function send(ApiRequestInterface $apiRequest)
    {
        return Retry::max(self::MAX_RETRIES)
            ->when(fn ($e) =>
                ($e instanceof ApiResponseUnsuccessfulException) &&
                $e->getStatusCode() === ResponseStatusCodes::UNAUTHORIZED
            )
            ->start(function (int $attempt) use ($apiRequest) {
                if ($attempt > 0) {
                    $this->accessToken->clear();
                }

                $apiRequest->addHeader('Authorization', "Bearer {$this->accessToken->getAccessToken()}");
                return $this->next->send($apiRequest);
            });
    }
}
