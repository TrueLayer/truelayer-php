<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Beneficiary;

use TrueLayer\Constants\BeneficiaryTypes;
use TrueLayer\Entities\Entity;
use TrueLayer\Interfaces\AccountIdentifier\AccountIdentifierInterface;
use TrueLayer\Interfaces\Beneficiary\ExternalAccountBeneficiaryInterface;
use TrueLayer\Validation\ValidType;

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
     * @var string[]
     */
    protected array $casts = [
        'account_identifier' => AccountIdentifierInterface::class,
    ];

    /**
     * @var string[]
     */
    protected array $arrayFields = [
        'account_holder_name',
        'account_identifier',
        'reference',
        'type',
    ];

    /**
     * @return mixed[]
     */
    protected function rules(): array
    {
        return [
            'account_holder_name' => 'nullable|string',
            'reference' => 'required|string',
            'account_identifier' => ['required', ValidType::of(AccountIdentifierInterface::class)],
        ];
    }

    /**
     * @return string|null
     */
    public function getAccountHolderName(): ?string
    {
        return $this->accountHolderName ?? null;
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
     * @return string|null
     */
    public function getReference(): ?string
    {
        return $this->reference ?? null;
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
}
