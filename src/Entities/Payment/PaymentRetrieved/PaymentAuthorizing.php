<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payment\PaymentRetrieved;

use TrueLayer\Interfaces\Payment\AuthorizationFlow\ActionInterface;
use TrueLayer\Interfaces\Payment\AuthorizationFlow\ConfigurationInterface;
use TrueLayer\Interfaces\Payment\PaymentAuthorizingInterface;
use TrueLayer\Validation\ValidType;

final class PaymentAuthorizing extends _PaymentWithAuthorizationConfig implements PaymentAuthorizingInterface
{
    /**
     * @var ConfigurationInterface
     */
    protected ConfigurationInterface $configuration;

    /**
     * @var ActionInterface
     */
    protected ActionInterface $nextAction;

    /**
     * @return array
     */
    protected function casts(): array
    {
        return \array_merge(parent::casts(), [
            'authorization_flow.actions.next' => ActionInterface::class,
        ]);
    }

    /**
     * @return mixed[]
     */
    protected function arrayFields(): array
    {
        return \array_merge(parent::arrayFields(), [
            'authorization_flow.actions.next' => 'next_action',
        ]);
    }

    /**
     * @return mixed[]
     */
    protected function rules(): array
    {
        return \array_merge(parent::rules(), [
            'authorization_flow' => 'required|array',
            'authorization_flow.actions.next' => ['nullable', ValidType::of(ActionInterface::class)],
        ]);
    }

    /**
     * @return ActionInterface|null
     */
    public function getAuthorizationFlowNextAction(): ?ActionInterface
    {
        return $this->nextAction ?? null;
    }
}
