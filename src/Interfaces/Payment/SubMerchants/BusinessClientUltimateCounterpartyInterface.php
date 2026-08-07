<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payment\SubMerchants;

use TrueLayer\Interfaces\AddressInterface;

interface BusinessClientUltimateCounterpartyInterface extends UltimateCounterpartyInterface
{
    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @param string $id
     *
     * @return $this
     */
    public function id(string $id): self;

    /**
     * @return string
     */
    public function getTradingName(): string;

    /**
     * @param string $tradingName
     *
     * @return $this
     */
    public function tradingName(string $tradingName): self;

    /**
     * @return string|null
     */
    public function getCommercialName(): ?string;

    /**
     * @param string $commercialName
     *
     * @return $this
     */
    public function commercialName(string $commercialName): self;

    /**
     * @return string|null
     */
    public function getUrl(): ?string;

    /**
     * @param string $url
     *
     * @return $this
     */
    public function url(string $url): self;

    /**
     * @return string|null
     */
    public function getMcc(): ?string;

    /**
     * @param string $mcc
     *
     * @return $this
     */
    public function mcc(string $mcc): self;

    /**
     * @return string|null
     */
    public function getRegistrationNumber(): ?string;

    /**
     * @param string $registrationNumber
     *
     * @return $this
     */
    public function registrationNumber(string $registrationNumber): self;

    /**
     * @return AddressInterface|null
     */
    public function getAddress(): ?AddressInterface;

    /**
     * @param AddressInterface|null $address
     *
     * @return AddressInterface
     */
    public function address(?AddressInterface $address = null): AddressInterface;
}
