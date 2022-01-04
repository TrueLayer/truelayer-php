<?php

declare(strict_types=1);

namespace TrueLayer\Services\Beneficiary;

use TrueLayer\Constants\BeneficiaryTypes;
use TrueLayer\Constants\ExternalAccountTypes;

final class IbanAccountBeneficiary extends AbstractExternalAccountBeneficiary
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

    /**
     * @return mixed[]
     */
    protected function rules(): array
    {
        return array_merge(parent::rules(), [
            'scheme_identifier.iban' => 'required|alpha_num|max:39|min:4'
        ]);
    }
}
