<?php

declare(strict_types=1);

namespace TrueLayer\Entities;

use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Interfaces\AddressInterface;
use TrueLayer\Interfaces\UserInterface;

final class User extends Entity implements UserInterface
{
    /**
     * @var string
     */
    protected string $id;

    /**
     * @var string
     */
    protected string $name;

    /**
     * @var string
     */
    protected string $email;

    /**
     * @var string
     */
    protected string $phone;

    /**
     * @var AddressInterface
     */
    protected AddressInterface $address;

    /**
     * @var string
     */
    protected string $dateOfBirth;

    /**
     * @var string[]
     */
    protected array $arrayFields = [
        'id',
        'name',
        'email',
        'phone',
        'address',
        'date_of_birth',
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
    public function getId(): ?string
    {
        return $this->id ?? null;
    }

    /**
     * @param string $id
     *
     * @return UserInterface
     */
    public function id(string $id): UserInterface
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name ?? null;
    }

    /**
     * @param string $name
     *
     * @return UserInterface
     */
    public function name(string $name): UserInterface
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email ?? null;
    }

    /**
     * @param string $email
     *
     * @return UserInterface
     */
    public function email(string $email): UserInterface
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone ?? null;
    }

    /**
     * @param string $phone
     *
     * @return UserInterface
     */
    public function phone(string $phone): UserInterface
    {
        $this->phone = $phone;

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
    public function getDateOfBirth(): ?string
    {
        return $this->dateOfBirth ?? null;
    }

    /**
     * @param string $dateOfBirth
     *
     * @return UserInterface
     */
    public function dateOfBirth(string $dateOfBirth): UserInterface
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }
}
