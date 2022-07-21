<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces;

use TrueLayer\Interfaces\Factories\ApiFactoryInterface;

interface HasApiFactoryInterface
{
    /**
     * @param ApiFactoryInterface $apiFactory
     *
     * @return $this
     */
    public function apiFactory(ApiFactoryInterface $apiFactory): self;

    /**
     * @return ApiFactoryInterface
     */
    public function getApiFactory(): ApiFactoryInterface;
}
