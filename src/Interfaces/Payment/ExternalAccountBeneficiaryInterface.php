<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payment;

use TrueLayer\Interfaces\AccountIdentifier\AccountIdentifierInterface;

interface ExternalAccountBeneficiaryInterface extends BeneficiaryInterface
{
    /**
     * @return AccountIdentifierInterface
     */
    public function getAccountIdentifier(): AccountIdentifierInterface;

    /**
     * @param AccountIdentifierInterface $accountIdentifier
     *
     * @return $this
     */
    public function accountIdentifier(AccountIdentifierInterface $accountIdentifier): self;
}
