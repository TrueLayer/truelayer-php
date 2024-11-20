<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces;

interface AddressRetrievedInterface extends ArrayableInterface, HasAttributesInterface
{
    /**
     * @return string|null
     */
    public function getAddressLine1(): ?string;

    /**
     * @return string|null
     */
    public function getAddressLine2(): ?string;

    /**
     * @return string|null
     */
    public function getCity(): ?string;

    /**
     * @return string|null
     */
    public function getState(): ?string;

    /**
     * @return string|null
     */
    public function getZip(): ?string;

    /**
     * @return string|null
     */
    public function getCountryCode(): ?string;
}
