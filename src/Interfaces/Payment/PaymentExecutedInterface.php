<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payment;

use TrueLayer\Interfaces\Payment\AuthorizationFlow\ConfigurationInterface;

interface PaymentExecutedInterface extends PaymentRetrievedInterface
{
    /**
     * @return \DateTimeInterface
     */
    public function getExecutedAt(): \DateTimeInterface;

    /**
     * @return ConfigurationInterface|null
     */
    public function getAuthorizationFlowConfig(): ?ConfigurationInterface;
}
