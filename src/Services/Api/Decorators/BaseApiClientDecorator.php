<?php

declare(strict_types=1);

namespace TrueLayer\Services\Api\Decorators;

use TrueLayer\Contracts\Api\ApiClientInterface;
use TrueLayer\Contracts\Api\ApiRequestInterface;
use TrueLayer\Services\Api\ApiRequest;

abstract class BaseApiClientDecorator implements ApiClientInterface
{
    /**
     * @var ApiClientInterface
     */
    protected ApiClientInterface $next;

    /**
     * @param ApiClientInterface $next
     */
    public function __construct(ApiClientInterface $next) {
        $this->next = $next;
    }

    /**
     * @return ApiRequestInterface
     */
    public function request(): ApiRequestInterface
    {
        return new ApiRequest($this);
    }
}
