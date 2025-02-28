<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces;

interface AddressInterface extends AddressRetrievedInterface
{
    /**
     * @param string $addressLine1
     *
     * @return AddressInterface
     */
    public function addressLine1(string $addressLine1): AddressInterface;

    /**
     * @param string $addressLine2
     *
     * @return AddressInterface
     */
    public function addressLine2(string $addressLine2): AddressInterface;

    /**
     * @param string $city
     *
     * @return AddressInterface
     */
    public function city(string $city): AddressInterface;

    /**
     * @param string $state
     *
     * @return AddressInterface
     */
    public function state(string $state): AddressInterface;

    /**
     * @param string $zip
     *
     * @return AddressInterface
     */
    public function zip(string $zip): AddressInterface;

    /**
     * @param string $countryCode
     *
     * @return AddressInterface
     */
    public function countryCode(string $countryCode): AddressInterface;
}
