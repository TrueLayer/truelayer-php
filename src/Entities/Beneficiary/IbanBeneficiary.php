<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Beneficiary;

use TrueLayer\Constants\BeneficiaryTypes;
use TrueLayer\Constants\ExternalAccountTypes;
use TrueLayer\Entities\Entity;
use TrueLayer\Interfaces\Beneficiary\IbanBeneficiaryInterface;

final class IbanBeneficiary extends Entity implements IbanBeneficiaryInterface
{
    /**
     * @var string
     */
    protected string $name;

    /**
     * @var string
     */
    protected string $reference;

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
    public function getName(): ?string
    {
        return $this->name ?? null;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function name(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getReference(): ?string
    {
        return $this->reference ?? null;
    }

    /**
     * @param string $reference
     *
     * @return $this
     */
    public function reference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

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
     * @return IbanBeneficiaryInterface
     */
    public function iban(string $iban): IbanBeneficiaryInterface
    {
        $this->iban = $iban;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return BeneficiaryTypes::EXTERNAL_ACCOUNT;
    }

    /**
     * @return string
     */
    public function getSchemeType(): string
    {
        return ExternalAccountTypes::IBAN;
    }
}
