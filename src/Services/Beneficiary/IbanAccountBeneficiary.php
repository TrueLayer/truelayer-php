<?php

declare(strict_types=1);

namespace TrueLayer\Services\Beneficiary;

use TrueLayer\Constants\ExternalAccountTypes;

class IbanAccountBeneficiary extends AbstractExternalAccountBeneficiary
{
    /**
     * @return string|null
     */
    public function getIban(): ?string
    {
        return $this->get('scheme_identifier.iban');
    }

    /**
     * @param string|null $iban
     *
     * @return IbanAccountBeneficiary
     */
    public function iban(string $iban = null): IbanAccountBeneficiary
    {
        return $this->set('scheme_identifier.iban', $iban);
    }

    /**
     * @return string
     */
    public function getSchemeType(): string
    {
        return ExternalAccountTypes::IBAN;
    }
}
