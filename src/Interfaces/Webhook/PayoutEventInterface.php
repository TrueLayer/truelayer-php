<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Webhook;

use TrueLayer\Interfaces\Webhook\Beneficiary\BeneficiaryInterface;

interface PayoutEventInterface extends EventInterface
{
    /**
     * Get the unique ID for the payout
     * @return string
     */
    public function getPayoutId(): string;

    /**
     * @return BeneficiaryInterface|null
     */
    public function getBeneficiary(): ?BeneficiaryInterface;
}
