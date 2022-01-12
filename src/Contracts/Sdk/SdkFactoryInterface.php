<?php

declare(strict_types=1);

namespace TrueLayer\Contracts\Sdk;

use TrueLayer\Exceptions\SignerException;

interface SdkFactoryInterface
{
    /**
     * @param SdkConfigInterface $config
     *
     * @throws SignerException
     *
     * @return SdkInterface
     */
    public function make(SdkConfigInterface $config): SdkInterface;
}
