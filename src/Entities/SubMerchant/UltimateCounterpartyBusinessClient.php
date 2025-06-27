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
     * @return UltimateCounterpartyBusinessClientInterface
     */
    public function address(?AddressInterface $address = null): UltimateCounterpartyBusinessClientInterface
    {
        $this->address = $address ?: $this->entityFactory->make(AddressInterface::class);

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
     * @throws InvalidArgumentException
     *
     * @return UltimateCounterpartyBusinessClientInterface
     */
    public function commercialName(string $commercialName): UltimateCounterpartyBusinessClientInterface
    {
        if (strlen($commercialName) > 100) {
            throw new InvalidArgumentException('Commercial name cannot exceed 100 characters');
        }

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
     * @throws InvalidArgumentException
     *
     * @return UltimateCounterpartyBusinessClientInterface
     */
    public function mcc(string $mcc): UltimateCounterpartyBusinessClientInterface
    {
        if (!preg_match('/^\d{4}$/', $mcc)) {
            throw new InvalidArgumentException('MCC must be exactly 4 digits');
        }

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
     * @throws InvalidArgumentException
     *
     * @return UltimateCounterpartyBusinessClientInterface
     */
    public function registrationNumber(string $registrationNumber): UltimateCounterpartyBusinessClientInterface
    {
        if (strlen($registrationNumber) > 50) {
            throw new InvalidArgumentException('Registration number cannot exceed 50 characters');
        }

        $this->registrationNumber = $registrationNumber;

        return $this;
    }

    /**
     * @return mixed[]
     * @throws InvalidArgumentException
     */
    public function toArray(): array
    {
        // Validate that either address or registration number is provided
        if (empty($this->address) && empty($this->registrationNumber)) {
            throw new InvalidArgumentException('Either address or registration number must be provided for business client');
        }

        $array = parent::toArray();
        
        // Remove empty address if registration number is used
        if (!empty($this->registrationNumber) && empty($this->address)) {
            unset($array['address']);
        }
        
        // Remove empty registration number if address is used
        if (!empty($this->address) && empty($this->registrationNumber)) {
            unset($array['registration_number']);
        }

        return $array;
    }
}