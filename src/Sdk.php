<?php

declare(strict_types=1);

namespace TrueLayer;

use TrueLayer\Interfaces\Sdk\SdkConfigInterface;
use TrueLayer\Services\Sdk\SdkConfig;
use TrueLayer\Factories\SdkFactory;

final class Sdk
{
    /**
     * @return SdkConfigInterface
     */
    public static function configure(): SdkConfigInterface
    {
        return SdkFactory::makeConfigurator();
    }
}
