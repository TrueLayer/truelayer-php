<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payment\AuthorizationFlow;

use TrueLayer\Entities\Entity;
use TrueLayer\Interfaces\Payment\AuthorizationFlow\ActionInterface;
use TrueLayer\Interfaces\Payment\AuthorizationFlow\AuthorizationFlowInterface;
use TrueLayer\Interfaces\Payment\AuthorizationFlow\AuthorizationFlowResponseInterface;

abstract class AuthorizationFlowResponse extends Entity implements AuthorizationFlowResponseInterface
{
    /**
     * @var string
     */
    protected string $status;

    /**
     * @var AuthorizationFlowInterface
     */
    protected AuthorizationFlowInterface $authorizationFlow;

    /**
     * @var string[]
     */
    protected array $casts = [
        'authorization_flow' => AuthorizationFlowInterface::class,
    ];

    /**
     * @var string[]
     */
    protected array $arrayFields = [
        'status',
        'authorization_flow',
    ];

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
