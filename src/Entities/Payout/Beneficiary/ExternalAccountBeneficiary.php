<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payout\Beneficiary;

use TrueLayer\Constants\BeneficiaryTypes;
use TrueLayer\Entities\Entity;
use TrueLayer\Interfaces\AccountIdentifier\AccountIdentifierInterface;
use TrueLayer\Interfaces\AddressInterface;
use TrueLayer\Interfaces\Payout\Beneficiary\ExternalAccountBeneficiaryInterface;

final class ExternalAccountBeneficiary extends Entity implements ExternalAccountBeneficiaryInterface
{
    /**
     * @var string
     */
    protected string $accountHolderName;

    /**
     * @var AccountIdentifierInterface
     */
    protected AccountIdentifierInterface $accountIdentifier;

    /**
     * @var string
     */
    protected string $reference;

    /**
     * @var string
     */
    protected string $dateOfBirth;

    /**
     * @var AddressInterface
     */
    protected AddressInterface $address;

    /**
     * @var string[]
     */
    protected array $casts = [
        'account_identifier' => AccountIdentifierInterface::class,
        'address' => AddressInterface::class,
    ];

    /**
     * @var string[]
     */
    protected array $arrayFields = [
        'account_holder_name',
        'account_identifier',
        'reference',
        'type',
        'date_of_birth',
        'address',
    ];

    /**
     * @return string
     */
    public function getAccountHolderName(): string
    {
        return $this->accountHolderName;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function accountHolderName(string $name): self
    {
        $this->accountHolderName = $name;

        return $this;
    }

    /**
     * @return AccountIdentifierInterface
     */
    public function getAccountIdentifier(): AccountIdentifierInterface
    {
        return $this->accountIdentifier;
    }

    /**
     * @param AccountIdentifierInterface $accountIdentifier
     *
     * @return $this
     */
    public function accountIdentifier(AccountIdentifierInterface $accountIdentifier): self
    {
        $this->accountIdentifier = $accountIdentifier;

        return $this;
    }

    /**
     * @return string
     */
    public function getReference(): string
    {
        return $this->reference;
    }

    /**
     * @param string $reference
     *
     * @return $this
     */
    public function reference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return BeneficiaryTypes::EXTERNAL_ACCOUNT;
    }

    /**
     * @param string $dateOfBirth
     *
     * @return $this
     */
    public function dateOfBirth(string $dateOfBirth): self
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
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
}
