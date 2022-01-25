<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Beneficiary;

interface ScanBeneficiaryInterface extends ExternalAccountBeneficiaryInterface
{
    /**
     * @param string $name
     *
     * @return $this
     */
    public function name(string $name): ScanBeneficiaryInterface;

    /**
     * @param string $sortCode
     *
     * @return ScanBeneficiaryInterface
     */
    public function sortCode(string $sortCode): ScanBeneficiaryInterface;

    /**
     * @return string|null
     */
    public function getAccountNumber(): ?string;

    /**
     * @param string $accountNumber
     *
     * @return ScanBeneficiaryInterface
     */
    public function accountNumber(string $accountNumber): ScanBeneficiaryInterface;
}
