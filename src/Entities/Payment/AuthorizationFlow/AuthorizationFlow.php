<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payment\AuthorizationFlow;

use TrueLayer\Attributes\Field;
use TrueLayer\Entities\Entity;
use TrueLayer\Interfaces\Payment\AuthorizationFlow\ActionInterface;
use TrueLayer\Interfaces\Payment\AuthorizationFlow\AuthorizationFlowInterface;
use TrueLayer\Interfaces\Payment\AuthorizationFlow\ConfigurationInterface;

final class AuthorizationFlow extends Entity implements AuthorizationFlowInterface
{
    /**
     * @var ActionInterface
     */
    #[Field('actions.next')]
    protected ActionInterface $nextAction;

    /**
     * @var ConfigurationInterface
     */
    #[Field]
    protected ConfigurationInterface $configuration;

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
