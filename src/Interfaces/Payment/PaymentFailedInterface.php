<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payment;

/**
 * A failed payment - it was authorized successfully, but the money did not move.
 */
interface PaymentFailedInterface extends PaymentFailureInterface
{
    /**
     * The date and time that TrueLayer determined that the payment was ready to be credited.
     *
     * @return \DateTimeInterface|null
     */
    public function getCreditableAt(): ?\DateTimeInterface;

    /**
     * The date and time at which TrueLayer determined that the payment's settlement was stalled, based on
     * the client's chosen delay or the default one.
     *
     * @return \DateTimeInterface|null
     */
    public function getSettlementStalledAt(): ?\DateTimeInterface;

    /**
     * The date and time the payment was reversed, if it was.
     *
     * @return \DateTimeInterface|null
     */
    public function getReversedAt(): ?\DateTimeInterface;

    /**
     * The reason for the payment reversal, one of `corrected`, `failed` or `disputed`.
     *
     * @return string|null
     */
    public function getReversalReason(): ?string;
}
