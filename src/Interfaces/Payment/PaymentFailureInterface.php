<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payment;

use TrueLayer\Interfaces\Payment\AuthorizationFlow\ConfigurationInterface;

interface PaymentFailureInterface extends PaymentRetrievedInterface
{
    /**
     * @return \DateTimeInterface
     */
    public function getFailedAt(): \DateTimeInterface;

    /**
     * @return string
     */
    public function getFailureStage(): string;

    /**
     * @return string|null
     */
    public function getFailureReason(): ?string;

    /**
     * @return ConfigurationInterface|null
     */
    public function getAuthorizationFlowConfig(): ?ConfigurationInterface;
}
