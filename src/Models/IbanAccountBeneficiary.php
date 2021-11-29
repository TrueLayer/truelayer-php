<?php

declare(strict_types=1);

namespace TrueLayer\Models;

use TrueLayer\Constants\ExternalAccountTypes;

class IbanAccountBeneficiary extends AbstractExternalAccountBeneficiary
{
    /**
     * @var string|null
     */
    private ?string $iban = null;

    /**
     * @return string|null
     */
    public function getIban(): ?string
    {
        return $this->iban;
    }

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
     * @return string
     */
    public function getSchemeType(): string
    {
        return ExternalAccountTypes::IBAN;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->wrap([
            'type' => $this->getSchemeType(),
            'iban' => $this->getIban(),
        ]);
    }
}
