<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payment;

use TrueLayer\Interfaces\Payment\AuthorizationFlow\ActionInterface;
use TrueLayer\Interfaces\Payment\AuthorizationFlow\ConfigurationInterface;

/**
 * The payment's authorization_flow has started, but authorization has not completed yet.
 */
interface PaymentAuthorizingInterface extends PaymentRetrievedInterface
{
    /**
     * The next action the user must take in the authorization flow, such as selecting a provider or being
     * redirected.
     *
     * @return ActionInterface|null
     */
    public function getAuthorizationFlowNextAction(): ?ActionInterface;

    /**
     * Information about the authorization flow the payment went through.
     *
     * @return ConfigurationInterface|null
     */
    public function getAuthorizationFlowConfig(): ?ConfigurationInterface;
}
