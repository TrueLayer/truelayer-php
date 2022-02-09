<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payment\AuthorizationFlow;

use TrueLayer\Constants\PaymentStatus;
use TrueLayer\Interfaces\Payment\AuthorizationFlow\AuthorizationFlowAuthorizationFailedInterface;
use TrueLayer\Validation\AllowedConstant;

final class AuthorizationFlowAuthorizationFailed extends AuthorizationFlowResponse implements AuthorizationFlowAuthorizationFailedInterface
{
    /**
     * @var string
     */
    protected string $failureStage;

    /**
     * @var string
     */
    protected string $failureReason;

    protected function arrayFields(): array
    {
        return \array_merge(parent::arrayFields(), [
            'failure_stage',
            'failure_reason',
        ]);
    }

    /**
     * @return mixed[]
     */
    protected function rules(): array
    {
        return \array_merge(parent::rules(), [
            'failure_stage' => ['required', AllowedConstant::in(PaymentStatus::class)],
            'failure_reason' => 'nullable|string',
        ]);
    }

    /**
     * @return string
     */
    public function getFailureStage(): string
    {
        return $this->failureStage;
    }

    /**
     * @return string|null
     */
    public function getFailureReason(): ?string
    {
        return $this->failureReason ?? null;
    }
}
