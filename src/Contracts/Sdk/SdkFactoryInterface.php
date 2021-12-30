<?php

declare(strict_types=1);

namespace TrueLayer\Contracts\Sdk;

interface SdkFactoryInterface
{
    /**
     * @param SdkConfigInterface $config
     *
     * @return SdkInterface
     */
    public function make(SdkConfigInterface $config): SdkInterface;
}
