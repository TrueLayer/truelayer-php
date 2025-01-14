<?php

declare(strict_types=1);

namespace TrueLayer\Entities\SignupPlus;

use TrueLayer\Entities\Entity;
use TrueLayer\Interfaces\AddressRetrievedInterface;
use TrueLayer\Interfaces\SignupPlus\SignupPlusAccountDetailsInterface;
use TrueLayer\Interfaces\SignupPlus\SignupPlusUserDataRetrievedInterface;

class SignupPlusUserDataRetrieved extends Entity implements SignupPlusUserDataRetrievedInterface
{
    /**
     * @var string
     */
    protected string $title;

    /**
     * @var string
     */
    protected string $firstName;

    /**
     * @var string
     */
    protected string $lastName;

    /**
     * @var string
     */
    protected string $dateOfBirth;

    /**
     * @var AddressRetrievedInterface
     */
    protected AddressRetrievedInterface $address;

    /**
     * @var string
     */
    protected string $nationalIdentificationNumber;

    /**
     * @var string
     */
    protected string $sex;

    /**
     * @var SignupPlusAccountDetailsInterface
     */
    protected SignupPlusAccountDetailsInterface $accountDetails;

    /**
     * @var string[]
     */
    protected array $casts = [
        'address' => AddressRetrievedInterface::class,
        'account_details' => SignupPlusAccountDetailsInterface::class,
    ];

    /**
     * @var string[]
     */
    protected array $arrayFields = [
        'title',
        'first_name',
        'last_name',
        'date_of_birth',
        'address',
        'national_identification_number',
        'sex',
        'account_details',
    ];

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title ?? null;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getDateOfBirth(): string
    {
        return $this->dateOfBirth;
    }

    public function getAddress(): AddressRetrievedInterface
    {
        return $this->address;
    }

    /**
     * @return string|null
     */
    public function getNationalIdentificationNumber(): ?string
    {
        return $this->nationalIdentificationNumber ?? null;
    }

    /**
     * @return string|null
     */
    public function getSex(): ?string
    {
        return $this->sex ?? null;
    }

    public function getAccountDetails(): SignupPlusAccountDetailsInterface
    {
        return $this->accountDetails;
    }
}
