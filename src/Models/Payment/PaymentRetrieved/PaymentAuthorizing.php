<?php

declare(strict_types=1);

namespace TrueLayer\Models\Payment\PaymentRetrieved;

use Illuminate\Support\Arr;
use TrueLayer\Constants\AuthorizationFlowActionTypes;
use TrueLayer\Contracts\Payment\AuthorizationFlow\ActionInterface;
use TrueLayer\Contracts\Payment\AuthorizationFlow\ConfigurationInterface;
use TrueLayer\Contracts\Payment\PaymentAuthorizingInterface;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Exceptions\ValidationException;
use TrueLayer\Models\Payment\PaymentRetrieved\AuthorizationFlow\Action\ProviderSelectionAction;
use TrueLayer\Models\Payment\PaymentRetrieved\AuthorizationFlow\Action\RedirectAction;
use TrueLayer\Models\Payment\PaymentRetrieved\AuthorizationFlow\Action\WaitAction;
use TrueLayer\Services\Util\Type;
use TrueLayer\Validation\ValidType;

final class PaymentAuthorizing extends _PaymentWithAuthorizationConfig implements PaymentAuthorizingInterface
{
    /**
     * @var class-string[]
     */
    private array $actionTypes = [
        AuthorizationFlowActionTypes::PROVIDER_SELECTION => ProviderSelectionAction::class,
        AuthorizationFlowActionTypes::REDIRECT => RedirectAction::class,
        AuthorizationFlowActionTypes::WAIT => WaitAction::class,
    ];

    /**
     * @var ConfigurationInterface
     */
    protected ConfigurationInterface $configuration;

    /**
     * @var ActionInterface
     */
    protected ActionInterface $nextAction;

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
        $types = array_values($this->actionTypes);

        return \array_merge(parent::rules(), [
            'authorization_flow' => 'required|array',
            'authorization_flow.actions.next' => ['nullable', ValidType::of(...$types)],
        ]);
    }

    /**
     * @return ActionInterface|null
     */
    public function getAuthorizationFlowNextAction(): ?ActionInterface
    {
        return $this->nextAction ?? null;
    }

    /**
     * @param mixed[] $data
     *
     * @throws InvalidArgumentException
     * @throws ValidationException
     *
     * @return $this
     */
    public function fill(array $data): self
    {
        $sdk = $this->getSdk();

        $next = Type::getNullableArray($data, 'authorization_flow.actions.next');
        $type = Type::getNullableString($data, 'authorization_flow.actions.next.type');

        if (isset($this->actionTypes[$type])) {
            Arr::set($data, 'authorization_flow.actions.next', $this->actionTypes[$type]::make($sdk)->fill($next));
        }

        return parent::fill($data);
    }
}
