<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payout;

interface BusinessAccountBeneficiaryInterface extends PayoutBeneficiaryInterface
{
    /**
     * @return string|null
     */
    public function getReference(): ?string;

    /**
     * @param string $reference
     *
     * @return BusinessAccountBeneficiaryInterface
     */
    public function reference(string $reference): BusinessAccountBeneficiaryInterface;
}
