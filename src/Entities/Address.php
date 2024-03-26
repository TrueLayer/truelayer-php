<?php

declare(strict_types=1);

namespace TrueLayer\Entities;

use TrueLayer\Interfaces\AddressInterface;

class Address extends Entity implements AddressInterface
{
    /**
     * @var string
     */
    protected string $addressLine1;

    /**
     * @var string
     */
    protected string $addressLine2;

    /**
     * @var string
     */
    protected string $city;

    /**
     * @var string
     */
    protected string $state;

    /**
     * @var string
     */
    protected string $zip;

    /**
     * @var string
     */
    protected string $countryCode;

    /**
     * @var string[]
     */
    protected array $arrayFields = [
        'address_line1',
        'address_line2',
        'city',
        'state',
        'zip',
        'country_code',
    ];

    /**
     * @return string|null
     */
    public function getAddressLine1(): ?string
    {
        return $this->addressLine1 ?? null;
    }

    /**
     * @param string $addressLine1
     *
     * @return AddressInterface
     */
    public function addressLine1(string $addressLine1): AddressInterface
    {
        $this->addressLine1 = $addressLine1;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAddressLine2(): ?string
    {
        return $this->addressLine2 ?? null;
    }

    /**
     * @param string $addressLine2
     *
     * @return AddressInterface
     */
    public function addressLine2(string $addressLine2): AddressInterface
    {
        $this->addressLine2 = $addressLine2;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city ?? null;
    }

    /**
     * @param string $city
     *
     * @return AddressInterface
     */
    public function city(string $city): AddressInterface
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getState(): ?string
    {
        return $this->state ?? null;
    }

    /**
     * @param string $state
     *
     * @return AddressInterface
     */
    public function state(string $state): AddressInterface
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getZip(): ?string
    {
        return $this->zip ?? null;
    }

    /**
     * @param string $zip
     *
     * @return AddressInterface
     */
    public function zip(string $zip): AddressInterface
    {
        $this->zip = $zip;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCountryCode(): ?string
    {
        return $this->countryCode ?? null;
    }

    /**
     * @param string $countryCode
     *
     * @return AddressInterface
     */
    public function countryCode(string $countryCode): AddressInterface
    {
        $this->countryCode = $countryCode;

        return $this;
    }
}
