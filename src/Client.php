<?php

declare(strict_types=1);

namespace TrueLayer;

use TrueLayer\Factories\ClientFactory;
use TrueLayer\Interfaces\Configuration\ClientConfigInterface;

final class Client
{
    /**
     * @return ClientConfigInterface
     */
    public static function configure(): ClientConfigInterface
    {
        return ClientFactory::makeConfigurator();
    }
}
