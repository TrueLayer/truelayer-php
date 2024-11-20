<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payment\Beneficiary;

use TrueLayer\Interfaces\Remitter\RemitterVerification\RemitterVerificationInterface;

interface MerchantBeneficiaryInterface extends BeneficiaryInterface
{
    /**
     * @return string
     */
    public function getMerchantAccountId(): string;

    /**
     * @param string $id
     *
     * @return MerchantBeneficiaryInterface
     */
    public function merchantAccountId(string $id): MerchantBeneficiaryInterface;

    /**
     * @return string|null
     */
    public function getAccountHolderName(): ?string;

    /**
     * @param string $name
     *
     * @return $this
     */
    public function accountHolderName(string $name): self;

    /**
     * @return string|null
     */
    public function getReference(): ?string;

    /**
     * @param string $reference
     *
     * @return $this
     */
    public function reference(string $reference): self;

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
