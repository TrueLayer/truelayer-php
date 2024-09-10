<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces;

interface AddressInterface extends ArrayableInterface, HasAttributesInterface
{
    /**
     * @return string|null
     */
    public function getAddressLine1(): ?string;

    /**
     * @param string $addressLine1
     * The full street address including house number and street name.
     * Pattern: `^.{1,50}$``
     *
     * @return AddressInterface
     */
    public function addressLine1(string $addressLine1): AddressInterface;

    /**
     * @return string|null
     */
    public function getAddressLine2(): ?string;

    /**
     * @param string $addressLine2
     * Further details like building name, suite, apartment number, etc.
     * Pattern: ^.{1,50}$
     *
     * @return AddressInterface
     */
    public function addressLine2(string $addressLine2): AddressInterface;

    /**
     * @return string|null
     */
    public function getCity(): ?string;

    /**
     * @param string $city
     * Name of the city / locality.
     * Pattern: ^.{1,50}$
     *
     * @return AddressInterface
     */
    public function city(string $city): AddressInterface;

    /**
     * @return string|null
     */
    public function getState(): ?string;

    /**
     * @param string $state
     * Name of the county / state.
     * Pattern: ^.{1,50}$
     *
     * @return AddressInterface
     */
    public function state(string $state): AddressInterface;

    /**
     * @return string|null
     */
    public function getZip(): ?string;

    /**
     * @param string $zip
     * Zip code or postal code.
     * Pattern: ^.{1,20}$
     *
     * @return AddressInterface
     */
    public function zip(string $zip): AddressInterface;

    /**
     * @return string|null
     */
    public function getCountryCode(): ?string;

    /**
     * @param string $countryCode
     * The country code according to ISO-3166-1 alpha-2
     *
     * @return AddressInterface
     */
    public function countryCode(string $countryCode): AddressInterface;
}
