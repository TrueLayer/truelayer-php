<?php

declare(strict_types=1);

namespace TrueLayer\Contracts\Payment;

use TrueLayer\Contracts\Payment\AuthorizationFlow\ConfigurationInterface;

interface PaymentAuthorizedInterface extends PaymentRetrievedInterface
{
    /**
     * @return ConfigurationInterface|null
     */
    public function getAuthorizationFlowConfig(): ?ConfigurationInterface;
}
