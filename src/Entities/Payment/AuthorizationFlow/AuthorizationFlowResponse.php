<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payment\AuthorizationFlow;

use TrueLayer\Constants\AuthorizationFlowStatusTypes;
use TrueLayer\Entities\Entity;
use TrueLayer\Interfaces\Payment\AuthorizationFlow\ActionInterface;
use TrueLayer\Interfaces\Payment\AuthorizationFlow\AuthorizationFlowInterface;
use TrueLayer\Interfaces\Payment\AuthorizationFlow\AuthorizationFlowResponseInterface;
use TrueLayer\Validation\AllowedConstant;
use TrueLayer\Validation\ValidType;

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
     * @return array
     */
    protected function rules(): array
    {
        return [
            'status' => ['required', AllowedConstant::in(AuthorizationFlowStatusTypes::class)],
            'authorization_flow' => ['nullable', ValidType::of(AuthorizationFlowInterface::class)],
        ];
    }

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
