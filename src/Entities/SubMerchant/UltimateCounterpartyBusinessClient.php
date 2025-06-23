<?php

declare(strict_types=1);

namespace TrueLayer\Entities\SubMerchant;

use TrueLayer\Entities\Entity;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Interfaces\AddressInterface;
use TrueLayer\Interfaces\SubMerchant\UltimateCounterpartyBusinessClientInterface;

final class UltimateCounterpartyBusinessClient extends Entity implements UltimateCounterpartyBusinessClientInterface
{
    /**
     * @var string
     */
    protected string $type = 'business_client';

    /**
     * @var AddressInterface
     */
    protected AddressInterface $address;

    /**
     * @var string
     */
    protected string $commercialName;

    /**
     * @var string
     */
    protected string $mcc;

    /**
     * @var string
     */
    protected string $registrationNumber;

    /**
     * @var string[]
     */
    protected array $arrayFields = [
        'type',
        'address',
        'commercial_name',
        'mcc',
        'registration_number',
    ];

    /**
     * @var string[]
     */
    protected array $casts = [
        'address' => AddressInterface::class,
    ];

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type ?? null;
    }

    /**
     * @param string $type
     *
     * @return UltimateCounterpartyBusinessClientInterface
     */
    public function type(string $type): UltimateCounterpartyBusinessClientInterface
    {
        $this->type = $type;

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
     * @throws InvalidArgumentException
     *
     * @return AddressInterface
     */
    public function address(?AddressInterface $address = null): AddressInterface
    {
        $this->address = $address ?: $this->entityFactory->make(AddressInterface::class);

        return $this->address;
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
     * @return UltimateCounterpartyBusinessClientInterface
     */
    public function commercialName(string $commercialName): UltimateCounterpartyBusinessClientInterface
    {
        $this->commercialName = $commercialName;

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
     * @return UltimateCounterpartyBusinessClientInterface
     */
    public function mcc(string $mcc): UltimateCounterpartyBusinessClientInterface
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
     * @return UltimateCounterpartyBusinessClientInterface
     */
    public function registrationNumber(string $registrationNumber): UltimateCounterpartyBusinessClientInterface
    {
        $this->registrationNumber = $registrationNumber;

        return $this;
    }
}