<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payment\AuthorizationFlow;

use TrueLayer\Interfaces\ArrayableInterface;

interface ConfigurationInterface extends ArrayableInterface
{
    /**
     * @return string|null
     */
    public function getRedirectReturnUri(): ?string;
}
