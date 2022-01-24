<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payment\PaymentRetrieved;

use TrueLayer\Entities\Payment\PaymentRetrieved;
use TrueLayer\Interfaces\Payment\AuthorizationFlow\ConfigurationInterface;
use TrueLayer\Validation\ValidType;

class _PaymentWithAuthorizationConfig extends PaymentRetrieved
{
    /**
     * @var ConfigurationInterface
     */
    protected ConfigurationInterface $configuration;

    /**
     * @return string[]
     */
    protected function casts(): array
    {
        return \array_merge(parent::casts(), [
            'authorization_flow.configuration' => ConfigurationInterface::class,
        ]);
    }

    /**
     * @return mixed[]
     */
    protected function arrayFields(): array
    {
        return \array_merge(parent::arrayFields(), [
            'authorization_flow.configuration' => 'configuration',
        ]);
    }

    /**
     * @return mixed[]
     */
    protected function rules(): array
    {
        return \array_merge(parent::rules(), [
            'authorization_flow' => ['nullable', 'array'],
            'authorization_flow.configuration' => ['nullable', ValidType::of(ConfigurationInterface::class)],
        ]);
    }

    /**
     * @return ConfigurationInterface|null
     */
    public function getAuthorizationFlowConfig(): ?ConfigurationInterface
    {
        return $this->configuration ?? null;
    }
}
