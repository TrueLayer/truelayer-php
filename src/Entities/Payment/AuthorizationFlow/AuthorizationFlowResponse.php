<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payment\AuthorizationFlow;

use TrueLayer\Attributes\Field;
use TrueLayer\Entities\Entity;
use TrueLayer\Interfaces\Payment\AuthorizationFlow\ActionInterface;
use TrueLayer\Interfaces\Payment\AuthorizationFlow\AuthorizationFlowInterface;
use TrueLayer\Interfaces\Payment\AuthorizationFlow\AuthorizationFlowResponseInterface;

abstract class AuthorizationFlowResponse extends Entity implements AuthorizationFlowResponseInterface
{
    /**
     * @var string
     */
    #[Field]
    protected string $status;

    /**
     * @var AuthorizationFlowInterface
     */
    #[Field]
    protected AuthorizationFlowInterface $authorizationFlow;

    /**
     * @return ActionInterface|null
     */
    public function getNextAction(): ?ActionInterface
    {
        return isset($this->authorizationFlow)
            ? $this->authorizationFlow->getNextAction()
            : null;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }
}
