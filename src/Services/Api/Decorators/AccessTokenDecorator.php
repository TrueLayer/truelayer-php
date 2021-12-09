<?php

declare(strict_types=1);

namespace TrueLayer\Services\Api\Decorators;

use TrueLayer\Contracts\Api\ApiClientInterface;
use TrueLayer\Contracts\Api\ApiRequestInterface;
use TrueLayer\Contracts\Auth\AuthTokenInterface;
use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiRequestValidationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\ApiResponseValidationException;

class AccessTokenDecorator extends BaseApiClientDecorator
{
    /**
     * @var AuthTokenInterface
     */
    private AuthTokenInterface $authToken;

    /**
     * @param ApiClientInterface $next
     * @param AuthTokenInterface $authToken
     */
    public function __construct(ApiClientInterface $next, AuthTokenInterface $authToken)
    {
        parent::__construct($next);
        $this->authToken = $authToken;
    }

    /**
     * @param ApiRequestInterface $apiRequest
     *
     * @throws ApiRequestJsonSerializationException
     * @throws ApiRequestValidationException
     * @throws ApiResponseUnsuccessfulException
     * @throws ApiResponseValidationException
     *
     * @return array
     */
    public function send(ApiRequestInterface $apiRequest): array
    {
        $apiRequest->addHeader('Authorization', "Bearer {$this->authToken->getAccessToken()}");
        return $this->next->send($apiRequest);
    }
}
