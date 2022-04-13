<?php

declare(strict_types=1);

namespace TrueLayer;

use TrueLayer\Factories\ClientFactory;
use TrueLayer\Interfaces\Client\ConfigInterface;

final class Client
{
    /**
     * @return ConfigInterface
     */
    public static function configure(): ConfigInterface
    {
        return ClientFactory::makeConfigurator();
    }
}
