<?php

declare(strict_types=1);

namespace TrueLayer\Models\Beneficiary;

use TrueLayer\Constants\BeneficiaryTypes;
use TrueLayer\Contracts\Beneficiary\BeneficiaryInterface;
use TrueLayer\Models\Model;

abstract class AbstractExternalAccountBeneficiary extends Model implements BeneficiaryInterface
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
     * @return string
     */
    public function getType(): string
    {
        return BeneficiaryTypes::EXTERNAL_ACCOUNT;
    }

    /**
     * @return string
     */
    abstract public function getSchemeType(): string;
}
