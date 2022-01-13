<?php

declare(strict_types=1);

namespace TrueLayer\Contracts\Payment\AuthorizationFlow;

use TrueLayer\Contracts\ArrayableInterface;

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
