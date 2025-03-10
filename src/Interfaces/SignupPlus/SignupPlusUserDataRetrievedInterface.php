<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\SignupPlus;

use TrueLayer\Interfaces\AddressRetrievedInterface;
use TrueLayer\Interfaces\HasAttributesInterface;

interface SignupPlusUserDataRetrievedInterface extends HasAttributesInterface
{
    /**
     * @return string|null
     */
    public function getTitle(): ?string;

    /**
     * @return string
     */
    public function getFirstName(): string;

    /**
     * @return string
     */
    public function getLastName(): string;

    /**
     * @return string
     */
    public function getDateOfBirth(): string;

    /**
     * @return AddressRetrievedInterface
     */
    public function getAddress(): AddressRetrievedInterface;

    /**
     * @return string|null
     */
    public function getNationalIdentificationNumber(): ?string;

    /**
     * @return string|null
     */
    public function getSex(): ?string;

    /**
     * @return SignupPlusAccountDetailsInterface
     */
    public function getAccountDetails(): SignupPlusAccountDetailsInterface;
}
