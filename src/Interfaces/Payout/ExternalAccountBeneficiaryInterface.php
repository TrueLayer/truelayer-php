<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payout;

use TrueLayer\Interfaces\AccountIdentifier\AccountIdentifierInterface;
use TrueLayer\Interfaces\AddressInterface;
use TrueLayer\Interfaces\Payout\PayoutBeneficiaryInterface;

interface ExternalAccountBeneficiaryInterface extends PayoutBeneficiaryInterface
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

    /**
     * @param string $dateOfBirth
     *
     * @return $this
     */
    public function dateOfBirth(string $dateOfBirth): self;

    /**
     * @param AddressInterface|null $address
     *
     * @return AddressInterface
     */
    public function address(?AddressInterface $address): AddressInterface;
}
