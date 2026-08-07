<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payment\SubMerchants;

use TrueLayer\Constants\UltimateCounterpartyTypes;
use TrueLayer\Entities\Entity;
use TrueLayer\Interfaces\AddressInterface;
use TrueLayer\Interfaces\Payment\SubMerchants\BusinessClientUltimateCounterpartyInterface;

final class BusinessClientUltimateCounterparty extends Entity implements BusinessClientUltimateCounterpartyInterface
{
    /**
     * @var string
     */
    protected string $id;

    /**
     * @var string
     */
    protected string $tradingName;

    /**
     * @var string
     */
    protected string $commercialName;

    /**
     * @var string
     */
    protected string $url;

    /**
     * @var string
     */
    protected string $mcc;

    /**
     * @var string
     */
    protected string $registrationNumber;

    /**
     * @var AddressInterface
     */
    protected AddressInterface $address;

    /**
     * @var string[]
     */
    protected array $casts = [
        'address' => AddressInterface::class,
    ];

    /**
     * @var string[]
     */
    protected array $arrayFields = [
        'id',
        'trading_name',
        'commercial_name',
        'url',
        'mcc',
        'registration_number',
        'address',
        'type',
    ];

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     *
     * @return $this
     */
    public function id(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getTradingName(): string
    {
        return $this->tradingName;
    }

    /**
     * @param string $tradingName
     *
     * @return $this
     */
    public function tradingName(string $tradingName): self
    {
        $this->tradingName = $tradingName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCommercialName(): ?string
    {
        return $this->commercialName ?? null;
    }

    /**
     * @param string $commercialName
     *
     * @return $this
     */
    public function commercialName(string $commercialName): self
    {
        $this->commercialName = $commercialName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getUrl(): ?string
    {
        return $this->url ?? null;
    }

    /**
     * @param string $url
     *
     * @return $this
     */
    public function url(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getMcc(): ?string
    {
        return $this->mcc ?? null;
    }

    /**
     * @param string $mcc
     *
     * @return $this
     */
    public function mcc(string $mcc): self
    {
        $this->mcc = $mcc;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getRegistrationNumber(): ?string
    {
        return $this->registrationNumber ?? null;
    }

    /**
     * @param string $registrationNumber
     *
     * @return $this
     */
    public function registrationNumber(string $registrationNumber): self
    {
        $this->registrationNumber = $registrationNumber;

        return $this;
    }

    /**
     * @return AddressInterface|null
     */
    public function getAddress(): ?AddressInterface
    {
        return $this->address ?? null;
    }

    /**
     * @param AddressInterface|null $address
     *
     * @return AddressInterface
     */
    public function address(?AddressInterface $address = null): AddressInterface
    {
        $this->address = $address ?: $this->entityFactory->make(AddressInterface::class);

        return $this->address;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return UltimateCounterpartyTypes::BUSINESS_CLIENT;
    }
}
