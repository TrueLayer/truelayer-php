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
     * A unique identifier for the user.
     * If you don’t provide this, TrueLayer generates a value in the response.
     * You can use the same value for multiple payments to indicate a returning user.
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
     * This is the full first and last name of your end user (not initials).
     * If you are using your own PISP licence this field is optional, otherwise it is required.
     * Pattern: ^[^\(\)]+$
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
     * The email address of your end user according to RFC 2822.
     * If you are using your own PISP licence this field is optional,
     * otherwise one of email/phone is required.
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
     * The phone number of your end user in formats recommended by ITU.
     * The country calling code must be included and prefixed with a +.
     * If you are using your own PISP licence this field is optional,
     * otherwise one of email/phone is required.
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
     * The physical address of your end user.
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
     * The date of birth of your end user, in YYYY-MM-DD format.
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
     * The user's political exposure (PEP), if known this field should be set to current otherwise to none.
     * If not known this field can be ignored or a null value can be sent.
     * @see \TrueLayer\Constants\UserPoliticalExposures
     *
     * @return UserInterface
     */
    public function politicalExposure(string $politicalExposure): UserInterface;
}
