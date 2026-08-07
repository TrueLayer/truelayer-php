<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payment;

use TrueLayer\Interfaces\Payment\AuthorizationFlow\ConfigurationInterface;

/**
 * An authorized payment - no more actions required, waiting for outcome.
 */
interface PaymentAuthorizedInterface extends PaymentRetrievedInterface
{
    /**
     * Information about the authorization flow the payment went through. Null if the payment transitioned
     * immediately to authorized or failed.
     *
     * @return ConfigurationInterface|null
     */
    public function getAuthorizationFlowConfig(): ?ConfigurationInterface;

    /**
     * The date and time that TrueLayer determined that the payment was ready to be credited.
     *
     * @return \DateTimeInterface|null
     */
    public function getCreditableAt(): ?\DateTimeInterface;
}
