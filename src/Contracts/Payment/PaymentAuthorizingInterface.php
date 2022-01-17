<?php

declare(strict_types=1);

namespace TrueLayer\Contracts\Payment;

use TrueLayer\Contracts\Payment\AuthorizationFlow\ActionInterface;
use TrueLayer\Contracts\Payment\AuthorizationFlow\ConfigurationInterface;

interface PaymentAuthorizingInterface extends PaymentRetrievedInterface
{
    /**
     * @return ActionInterface|null
     */
    public function getAuthorizationFlowNextAction(): ?ActionInterface;

    /**
     * @return ConfigurationInterface|null
     */
    public function getAuthorizationFlowConfig(): ?ConfigurationInterface;
}