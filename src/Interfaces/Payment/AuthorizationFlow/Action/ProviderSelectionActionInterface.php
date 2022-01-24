<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payment\AuthorizationFlow\Action;

use TrueLayer\Interfaces\Provider\ProviderInterface;

interface ProviderSelectionActionInterface extends ActionInterface
{
    /**
     * @return ProviderInterface[]
     */
    public function getProviders(): array;
}
