<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payment;

use TrueLayer\Interfaces\Payment\AuthorizationFlow\ConfigurationInterface;

/**
 * An executed payment - the bank has accepted the payment.
 */
interface PaymentExecutedInterface extends PaymentRetrievedInterface
{
    /**
     * The date and time the payment executed at.
     *
     * @return \DateTimeInterface
     */
    public function getExecutedAt(): \DateTimeInterface;

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
