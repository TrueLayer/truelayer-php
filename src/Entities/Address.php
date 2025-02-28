<?php

declare(strict_types=1);

namespace TrueLayer\Entities;

use TrueLayer\Interfaces\AddressInterface;

class Address extends AddressRetrieved implements AddressInterface
{
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
