<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payment\AuthorizationFlow;

use TrueLayer\Interfaces\Payment\AuthorizationFlow\AuthorizationFlowAuthorizationFailedInterface;

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
