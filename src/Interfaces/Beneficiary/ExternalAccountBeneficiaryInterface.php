<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Beneficiary;

interface ExternalAccountBeneficiaryInterface extends BeneficiaryInterface
{
    /**
     * @return string|null
     */
    public function getName(): ?string;

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
     * @return string
     */
    public function getType(): string;

    /**
     * @return string
     */
    public function getSchemeType(): string;
}
