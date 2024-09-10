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
     * The unique identifier of a TrueLayer merchant account.
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
     * The verification of the remitter info.
     * If specified, the API performs additional checks on the remitter information.
     * @see \TrueLayer\Interfaces\Client\ClientInterface::remitterVerification()
     * @return MerchantBeneficiaryInterface
     */
    public function verification(RemitterVerificationInterface $verification): MerchantBeneficiaryInterface;
}
