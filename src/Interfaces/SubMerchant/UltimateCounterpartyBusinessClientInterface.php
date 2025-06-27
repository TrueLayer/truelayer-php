<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\SubMerchant;

use TrueLayer\Interfaces\AddressInterface;

interface UltimateCounterpartyBusinessClientInterface extends UltimateCounterpartyInterface
{
    /**
     * @return AddressInterface|null
     */
    public function getAddress(): ?AddressInterface;

    /**
     * @param AddressInterface|null $address
     *
     * @return UltimateCounterpartyBusinessClientInterface
     */
    public function address(?AddressInterface $address): UltimateCounterpartyBusinessClientInterface;

    /**
     * @return string|null
     */
    public function getCommercialName(): ?string;

    /**
     * @param string $commercialName
     *
     * @return UltimateCounterpartyBusinessClientInterface
     */
    public function commercialName(string $commercialName): UltimateCounterpartyBusinessClientInterface;

    /**
     * @return string|null
     */
    public function getMcc(): ?string;

    /**
     * @param string $mcc
     *
     * @return UltimateCounterpartyBusinessClientInterface
     */
    public function mcc(string $mcc): UltimateCounterpartyBusinessClientInterface;

    /**
     * @return string|null
     */
    public function getRegistrationNumber(): ?string;

    /**
     * @param string $registrationNumber
     *
     * @return UltimateCounterpartyBusinessClientInterface
     */
    public function registrationNumber(string $registrationNumber): UltimateCounterpartyBusinessClientInterface;
}