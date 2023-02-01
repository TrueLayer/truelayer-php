<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Beneficiary;

interface MerchantBeneficiaryInterface extends BeneficiaryInterface
{
    /**
     * @return string|null
     */
    public function getMerchantAccountId(): ?string;

    /**
     * @param string $id
     *
     * @return MerchantBeneficiaryInterface
     */
    public function merchantAccountId(string $id): MerchantBeneficiaryInterface;
}
