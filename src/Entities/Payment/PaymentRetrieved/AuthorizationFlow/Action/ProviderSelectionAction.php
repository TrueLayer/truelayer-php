<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payment\PaymentRetrieved\AuthorizationFlow\Action;

use TrueLayer\Constants\AuthorizationFlowActionTypes;
use TrueLayer\Entities\Payment\PaymentRetrieved\AuthorizationFlow\Action;
use TrueLayer\Interfaces\Payment\AuthorizationFlow\Action\ProviderSelectionActionInterface;
use TrueLayer\Interfaces\Provider\ProviderInterface;
use TrueLayer\Validation\ValidType;

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
     * @return mixed[]
     */
    protected function rules(): array
    {
        return [
            'providers.*' => [ValidType::of(ProviderInterface::class)],
        ];
    }

    /**
     * @return ProviderInterface[]
     */
    public function getProviders(): array
    {
        return $this->providers;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return AuthorizationFlowActionTypes::PROVIDER_SELECTION;
    }
}
