<?php

declare(strict_types=1);

namespace TrueLayer;

use TrueLayer\Factories\SdkFactory;
use TrueLayer\Interfaces\Sdk\SdkConfigInterface;

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
