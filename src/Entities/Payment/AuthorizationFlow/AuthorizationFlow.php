<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payment\AuthorizationFlow;

use TrueLayer\Entities\Entity;
use TrueLayer\Interfaces\Payment\AuthorizationFlow\ActionInterface;
use TrueLayer\Interfaces\Payment\AuthorizationFlow\AuthorizationFlowInterface;
use TrueLayer\Interfaces\Payment\AuthorizationFlow\ConfigurationInterface;
use TrueLayer\Validation\ValidType;

final class AuthorizationFlow extends Entity implements AuthorizationFlowInterface
{
    /**
     * @var ActionInterface
     */
    protected ActionInterface $nextAction;

    /**
     * @var ConfigurationInterface
     */
    protected ConfigurationInterface $configuration;

    protected array $casts = [
        'actions.next' => ActionInterface::class,
        'configuration' => ConfigurationInterface::class,
    ];

    protected array $arrayFields = [
        'actions.next' => 'next_action',
        'configuration' => 'configuration',
    ];

    /**
     * @return mixed[]
     */
    protected function rules(): array
    {
        return [
            'actions.next' => ['nullable', ValidType::of(ActionInterface::class)],
            'configuration' => ['nullable', ValidType::of(ConfigurationInterface::class)],
        ];
    }

    /**
     * @return ConfigurationInterface|null
     */
    public function getConfig(): ?ConfigurationInterface
    {
        return $this->configuration ?? null;
    }

    /**
     * @return ActionInterface|null
     */
    public function getNextAction(): ?ActionInterface
    {
        return $this->nextAction ?? null;
    }
}
