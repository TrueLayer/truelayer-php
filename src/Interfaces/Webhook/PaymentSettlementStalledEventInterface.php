<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Webhook;

use DateTimeInterface;

interface PaymentSettlementStalledEventInterface extends PaymentEventInterface
{
    /**
     * Get the date and time at which TrueLayer determined that the payment's settlement was stalled based on
     * the client's chosen delay or the default one.
     *
     * @return DateTimeInterface
     */
    public function getSettlementStalledAt(): DateTimeInterface;
}
