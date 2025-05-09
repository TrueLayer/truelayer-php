<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\MerchantAccount\Transactions;

use TrueLayer\Interfaces\Payout\Beneficiary\BeneficiaryInterface;

interface MerchantAccountPayoutInterface
{
    /**
     * @return \DateTimeInterface
     */
    public function getCreatedAt(): \DateTimeInterface;

    /**
     * @return string
     */
    public function getContextCode(): string;

    /**
     * @return string
     */
    public function getPayoutId(): string;

    /**
     * @return BeneficiaryInterface
     */
    public function getBeneficiary(): BeneficiaryInterface;

    /**
     * @return array<string, string>
     */
    public function getMetadata(): array;
}
