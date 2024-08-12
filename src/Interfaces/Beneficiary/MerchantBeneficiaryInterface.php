<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Beneficiary;

use TrueLayer\Interfaces\Remitter\RemitterVerification\RemitterVerificationInterface;

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

    /**
     * @return RemitterVerificationInterface|null
     */
    public function getVerification(): ?RemitterVerificationInterface;

    /**
     * @param RemitterVerificationInterface $verification
     *
     * @return MerchantBeneficiaryInterface
     */
    public function verification(RemitterVerificationInterface $verification): MerchantBeneficiaryInterface;
}
