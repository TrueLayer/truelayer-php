<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payment\AuthorizationFlow\Action;

use TrueLayer\Entities\Payment\AuthorizationFlow\Action;
use TrueLayer\Interfaces\Payment\AuthorizationFlow\Action\ProviderSelectionActionInterface;
use TrueLayer\Interfaces\Provider\ProviderInterface;

class ProviderSelectionAction extends Action implements ProviderSelectionActionInterface
{
    /**
     * @var ProviderInterface[]
     */
    protected array $providers = [];

    /**
     * @var array|string[]
     */
    protected array $arrayFields = [
        'type',
        'providers',
    ];

    /**
     * @var string[]
     */
    protected array $casts = [
        'providers.*' => ProviderInterface::class,
    ];

    /**
     * @return ProviderInterface[]
     */
    public function getProviders(): array
    {
        return $this->providers;
    }
}
