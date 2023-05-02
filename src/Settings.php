<?php

declare(strict_types=1);

namespace TrueLayer;

final class Settings
{
    /**
     * @var string|null
     */
    private static ?string $tlAgent = null;

    public static function tlAgent(string $tlAgent)
    {
        self::$tlAgent = $tlAgent;
    }

    public static function getTlAgent(): ?string
    {
        return self::$tlAgent;
    }
}
