<?php

declare(strict_types=1);

namespace TrueLayer\Services\ApiClient\Decorators;

use TrueLayer\Contracts\ApiClient\ApiClientInterface;
use TrueLayer\Contracts\ApiClient\ApiRequestInterface;
use TrueLayer\Services\ApiClient\ApiRequest;

abstract class BaseApiClientDecorator implements ApiClientInterface
{
    /**
     * @var ApiClientInterface
     */
    protected ApiClientInterface $next;

    /**
     * @param ApiClientInterface $next
     */
    public function __construct(ApiClientInterface $next)
    {
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
