<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Beneficiary;

interface IbanBeneficiaryInterface extends ExternalAccountBeneficiaryInterface
{
    /**
     * @return string|null
     */
    public function getIban(): ?string;

    /**
     * @param string $iban
     *
     * @return IbanBeneficiaryInterface
     */
    public function iban(string $iban): IbanBeneficiaryInterface;
}
