<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payment\AuthorizationFlow\Action;

use TrueLayer\Attributes\ArrayShape;
use TrueLayer\Attributes\Field;
use TrueLayer\Entities\Payment\AuthorizationFlow\Action;
use TrueLayer\Interfaces\Payment\AuthorizationFlow\Action\ProviderSelectionActionInterface;
use TrueLayer\Interfaces\Provider\ProviderInterface;

class ProviderSelectionAction extends Action implements ProviderSelectionActionInterface
{
    /**
     * @var ProviderInterface[]
     */
    #[Field, ArrayShape(ProviderInterface::class)]
    protected array $providers = [];

    /**
     * @return ProviderInterface[]
     */
    public function getProviders(): array
    {
        return $this->providers;
    }
}
