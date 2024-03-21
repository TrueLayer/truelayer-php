<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payment\AuthorizationFlow;

use TrueLayer\Entities\Entity;
use TrueLayer\Interfaces\Payment\AuthorizationFlow\ActionInterface;
use TrueLayer\Interfaces\Payment\AuthorizationFlow\AuthorizationFlowInterface;
use TrueLayer\Interfaces\Payment\AuthorizationFlow\ConfigurationInterface;

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
