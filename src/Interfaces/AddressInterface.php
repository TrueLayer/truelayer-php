<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces;

interface AddressInterface extends ArrayableInterface, HasAttributesInterface
{
    /**
     * @return string
     */
    public function getAddressLine1(): string;

    /**
     * @param string $addressLine1
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
     *
     * @return AddressInterface
     */
    public function addressLine2(string $addressLine2): AddressInterface;

    /**
     * @return string
     */
    public function getCity(): string;

    /**
     * @param string $city
     *
     * @return AddressInterface
     */
    public function city(string $city): AddressInterface;

    /**
     * @return string
     */
    public function getState(): string;

    /**
     * @param string $state
     *
     * @return AddressInterface
     */
    public function state(string $state): AddressInterface;

    /**
     * @return string
     */
    public function getZip(): string;

    /**
     * @param string $zip
     *
     * @return AddressInterface
     */
    public function zip(string $zip): AddressInterface;

    /**
     * @return string
     */
    public function getCountryCode(): string;

    /**
     * @param string $countryCode
     *
     * @return AddressInterface
     */
    public function countryCode(string $countryCode): AddressInterface;
}
