<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces;

interface UserInterface extends ArrayableInterface, HasAttributesInterface
{
    /**
     * @return string|null
     */
    public function getId(): ?string;

    /**
     * @param string $id
     *
     * @return UserInterface
     */
    public function id(string $id): UserInterface;

    /**
     * @return string|null
     */
    public function getName(): ?string;

    /**
     * @param string $name
     *
     * @return UserInterface
     */
    public function name(string $name): UserInterface;

    /**
     * @return string|null
     */
    public function getEmail(): ?string;

    /**
     * @param string $email
     *
     * @return UserInterface
     */
    public function email(string $email): UserInterface;

    /**
     * @return string|null
     */
    public function getPhone(): ?string;

    /**
     * @param string $phone
     *
     * @return UserInterface
     */
    public function phone(string $phone): UserInterface;

    /**
     * @return AddressInterface|null
     */
    public function getAddress(): ?AddressInterface;

    /**
     * @param AddressInterface|null $address
     *
     * @return AddressInterface
     */
    public function address(?AddressInterface $address): AddressInterface;

    /**
     * @return string|null
     */
    public function getDateOfBirth(): ?string;

    /**
     * @param string $dateOfBirth
     *
     * @return UserInterface
     */
    public function dateOfBirth(string $dateOfBirth): UserInterface;

    /**
     * @return string|null
     */
    public function getPoliticalExposure(): ?string;

    /**
     * @param string $politicalExposure
     *
     * @return UserInterface
     */
    public function politicalExposure(string $politicalExposure): UserInterface;
}
