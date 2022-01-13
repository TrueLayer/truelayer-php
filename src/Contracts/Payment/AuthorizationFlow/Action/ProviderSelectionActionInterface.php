<?php

declare(strict_types=1);

namespace TrueLayer\Contracts\Payment\AuthorizationFlow\Action;

use TrueLayer\Contracts\ArrayableInterface;
use TrueLayer\Contracts\ProviderInterface;

interface ProviderSelectionActionInterface extends ArrayableInterface
{
    /**
     * @return ProviderInterface[]
     */
    public function getProviders(): array;

    /**
     * @return string
     */
    public function getType(): string;
}
