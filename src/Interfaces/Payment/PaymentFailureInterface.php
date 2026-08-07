<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payment;

use TrueLayer\Interfaces\Payment\AuthorizationFlow\ConfigurationInterface;

/**
 * Fields shared by a payment that was authorized successfully, but the money did not move (either
 * permanently, via `PaymentFailedInterface`, or for a single retried attempt, via
 * `PaymentAttemptFailedInterface`).
 */
interface PaymentFailureInterface extends PaymentRetrievedInterface
{
    /**
     * The date and time the payment was marked as failed.
     *
     * @return \DateTimeInterface
     */
    public function getFailedAt(): \DateTimeInterface;

    /**
     * The status the payment was in when it failed, one of `authorization_required`, `authorizing`,
     * `authorized` or `executed`.
     *
     * @return string
     */
    public function getFailureStage(): string;

    /**
     * The reason the payment failed. Handle unexpected values gracefully as an unknown failure.
     *
     * @return string|null
     */
    public function getFailureReason(): ?string;

    /**
     * Information about the authorization flow the payment went through. Null if the payment transitioned
     * immediately to authorized or failed.
     *
     * @return ConfigurationInterface|null
     */
    public function getAuthorizationFlowConfig(): ?ConfigurationInterface;
}
