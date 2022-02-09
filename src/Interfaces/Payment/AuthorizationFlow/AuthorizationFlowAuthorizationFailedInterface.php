<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payment\AuthorizationFlow;

interface AuthorizationFlowAuthorizationFailedInterface extends AuthorizationFlowResponseInterface
{
    /**
     * @return string
     */
    public function getFailureStage(): string;

    /**
     * @return string|null
     */
    public function getFailureReason(): ?string;
}
