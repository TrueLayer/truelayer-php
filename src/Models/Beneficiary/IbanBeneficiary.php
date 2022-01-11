<?php

declare(strict_types=1);

namespace TrueLayer\Models\Beneficiary;

use TrueLayer\Constants\ExternalAccountTypes;

final class IbanBeneficiary extends AbstractExternalAccountBeneficiary
{
    /**
     * @var string
     */
    protected string $iban;

    /**
     * @var string[]
     */
    protected array $arrayFields = [
        'type',
        'name',
        'reference',
        'scheme_identifier.type' => 'scheme_type',
        'scheme_identifier.iban' => 'iban',
    ];

    /**
     * @var string[]
     */
    protected array $rules = [
        'name' => 'required|string',
        'reference' => 'required|string',
        'scheme_identifier.iban' => 'required|alpha_num|max:39|min:4',
    ];

    /**
     * @return string|null
     */
    public function getIban(): ?string
    {
        return $this->iban ?? null;
    }

    /**
     * @param string $iban
     *
     * @return IbanBeneficiary
     */
    public function iban(string $iban): IbanBeneficiary
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
}
