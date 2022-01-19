<?php

declare(strict_types=1);

namespace TrueLayer\Models\Payment\PaymentRetrieved\AuthorizationFlow\Action;

use TrueLayer\Constants\AuthorizationFlowActionTypes;
use TrueLayer\Contracts\Payment\AuthorizationFlow\Action\ProviderSelectionActionInterface;
use TrueLayer\Contracts\ProviderInterface;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Exceptions\ValidationException;
use TrueLayer\Models\Payment\PaymentRetrieved\AuthorizationFlow\Action;
use TrueLayer\Models\Provider\Provider;
use TrueLayer\Services\Util\Type;
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
     * @return mixed[]
     */
    protected function rules(): array
    {
        return [
            'providers.*' => [ValidType::of(Provider::class)],
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

    /**
     * @param mixed[] $data
     *
     * @throws ValidationException
     * @throws InvalidArgumentException
     *
     * @return $this
     */
    public function fill(array $data): self
    {
        if ($providers = Type::getNullableArray($data, 'providers')) {
            $data['providers'] = \array_map(
                fn ($provider) => Provider::make($this->getSdk())->fill(
                    \is_array($provider) ? $provider : []
                ),
                $providers
            );
        }

        return parent::fill($data);
    }
}
