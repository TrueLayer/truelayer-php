<?php

declare(strict_types=1);

namespace TrueLayer\Entities;

use TrueLayer\Interfaces\AddressRetrievedInterface;

class AddressRetrieved extends Entity implements AddressRetrievedInterface
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
     * @return string|null
     */
    public function getAddressLine2(): ?string
    {
        return $this->addressLine2 ?? null;
    }

    /**
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city ?? null;
    }

    /**
     * @return string|null
     */
    public function getState(): ?string
    {
        return $this->state ?? null;
    }

    /**
     * @return string|null
     */
    public function getZip(): ?string
    {
        return $this->zip ?? null;
    }

    /**
     * @return string|null
     */
    public function getCountryCode(): ?string
    {
        return $this->countryCode ?? null;
    }
}
