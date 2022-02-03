<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Beneficiary;

use TrueLayer\Interfaces\AccountIdentifier\AccountIdentifierInterface;

interface ExternalAccountBeneficiaryInterface extends BeneficiaryInterface
{
    /**
     * @return AccountIdentifierInterface
     */
    public function getAccountIdentifier(): AccountIdentifierInterface;

    /**
     * @param AccountIdentifierInterface $accountIdentifier
     * @return $this
     */
    public function accountIdentifier(AccountIdentifierInterface $accountIdentifier): self;

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
}
