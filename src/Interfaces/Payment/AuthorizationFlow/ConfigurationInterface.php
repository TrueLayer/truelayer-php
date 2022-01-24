<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payment\AuthorizationFlow;

use TrueLayer\Interfaces\ArrayableInterface;

interface ConfigurationInterface extends ArrayableInterface
{
    /**
     * @return bool
     */
    public function isProviderSelectionSupported(): bool;

    /**
     * @return bool
     */
    public function isRedirectSupported(): bool;

    /**
     * @return string|null
     */
    public function getRedirectReturnUri(): ?string;
}
