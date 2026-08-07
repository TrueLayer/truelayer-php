<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Webhook;

interface PaymentFundsReceivedEventInterface extends PaymentEventInterface
{
    /**
     * Get the date and time that the Direct Debit payment arrived in the merchant account.
     * The payment can still be reversed at this stage.
     *
     * @return \DateTimeInterface
     */
    public function getFundsReceivedAt(): \DateTimeInterface;
}
