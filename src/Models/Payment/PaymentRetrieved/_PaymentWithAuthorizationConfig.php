<?php

declare(strict_types=1);

namespace TrueLayer\Models\Payment\PaymentRetrieved;

use Illuminate\Support\Arr;
use TrueLayer\Contracts\Payment\AuthorizationFlow\ConfigurationInterface;
use TrueLayer\Contracts\Payment\PaymentAuthorizedInterface;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Exceptions\ValidationException;
use TrueLayer\Models\Model;
use TrueLayer\Models\Payment\PaymentRetrieved;
use TrueLayer\Models\Payment\PaymentRetrieved\AuthorizationFlow\Configuration;
use TrueLayer\Services\Util\Type;
use TrueLayer\Validation\ValidType;

class _PaymentWithAuthorizationConfig extends PaymentRetrieved
{
    /**
     * @var ConfigurationInterface
     */
    protected ConfigurationInterface $configuration;

    /**
     * @return mixed[]
     */
    protected function arrayFields(): array
    {
        return array_merge(parent::arrayFields(), [
            'authorization_flow.configuration' => 'configuration',
        ]);
    }

    /**
     * @return mixed[]
     */
    protected function rules(): array
    {
        return array_merge(parent::rules(), [
            'authorization_flow' => 'required|array',
            'authorization_flow.configuration' => [ 'required', ValidType::of(Configuration::class) ],
        ]);
    }

    /**
     * @return ConfigurationInterface
     */
    public function getAuthorizationFlowConfig(): ConfigurationInterface
    {
        return $this->configuration;
    }

    /**
     * @param mixed[] $data
     * @return $this
     * @throws InvalidArgumentException
     * @throws ValidationException
     */
    public function fill(array $data): self
    {
        if ($config = Type::getNullableArray($data, 'authorization_flow.configuration')) {
            Arr::set($data, 'authorization_flow.configuration', Configuration::make($this->getSdk())->fill($config));
        }

        return parent::fill($data);
    }
}
