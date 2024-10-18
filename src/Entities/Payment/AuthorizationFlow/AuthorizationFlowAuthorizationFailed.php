<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payment\AuthorizationFlow;

use TrueLayer\Attributes\Field;
use TrueLayer\Interfaces\Payment\AuthorizationFlow\AuthorizationFlowAuthorizationFailedInterface;

final class AuthorizationFlowAuthorizationFailed extends AuthorizationFlowResponse implements AuthorizationFlowAuthorizationFailedInterface
{
    /**
     * @var string
     */
    #[Field]
    protected string $failureStage;

    /**
     * @var string
     */
    #[Field]
    protected string $failureReason;

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
