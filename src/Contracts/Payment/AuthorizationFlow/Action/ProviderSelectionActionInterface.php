<?php

declare(strict_types=1);

namespace TrueLayer\Contracts\Payment\AuthorizationFlow\Action;

use TrueLayer\Contracts\ProviderInterface;

interface ProviderSelectionActionInterface extends ActionInterface
{
    /**
     * @return ProviderInterface[]
     */
    public function getProviders(): array;
}
