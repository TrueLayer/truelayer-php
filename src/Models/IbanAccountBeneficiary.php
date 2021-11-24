<?php

declare(strict_types=1);

namespace TrueLayer\Models;

use TrueLayer\Constants\ExternalAccountTypes;

class IbanAccountBeneficiary extends AbstractExternalAccountBeneficiary
{
    /**
     * @var string
     */
    private string $iban;

    /**
     * @param string $iban
     *
     * @return IbanAccountBeneficiary
     */
    public function iban(string $iban): IbanAccountBeneficiary
    {
        $this->iban = $iban;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->wrap([
            'type' => ExternalAccountTypes::IBAN,
            'iban' => $this->iban,
        ]);
    }
}
