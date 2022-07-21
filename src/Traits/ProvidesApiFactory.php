<?php

declare(strict_types=1);

namespace TrueLayer\Traits;

use TrueLayer\Interfaces\Factories\ApiFactoryInterface;

trait ProvidesApiFactory
{
    /**
     * @var ApiFactoryInterface
     */
    private ApiFactoryInterface $apiFactory;

    /**
     * @param ApiFactoryInterface $apiFactory
     *
     * @return $this
     */
    public function apiFactory(ApiFactoryInterface $apiFactory): self
    {
        $this->apiFactory = $apiFactory;

        return $this;
    }

    /**
     * @return ApiFactoryInterface
     */
    public function getApiFactory(): ApiFactoryInterface
    {
        return $this->apiFactory;
    }
}
