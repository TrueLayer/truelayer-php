<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Beneficiary;

use TrueLayer\Constants\BeneficiaryTypes;
use TrueLayer\Entities\Entity;
use TrueLayer\Interfaces\Beneficiary\MerchantBeneficiaryInterface;

final class MerchantBeneficiary extends Entity implements MerchantBeneficiaryInterface
{
    /**
     * @var string
     */
    protected string $merchantAccountId;

    /**
     * @var string
     */
    protected string $accountHolderName;

    /**
     * @var string
     */
    protected string $reference;

    /**
     * @var string[]
     */
    protected array $arrayFields = [
        'merchant_account_id',
        'account_holder_name',
        'reference',
        'type',
    ];

    /**
     * @return string|null
     */
    public function getMerchantAccountId(): ?string
    {
        return $this->merchantAccountId ?? null;
    }

    /**
     * @param string $id
     *
     * @return MerchantBeneficiary
     */
    public function merchantAccountId(string $id): MerchantBeneficiary
    {
        $this->merchantAccountId = $id;

        return $this;
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
     * @return MerchantBeneficiary
     */
    public function accountHolderName(string $name): MerchantBeneficiary
    {
        $this->accountHolderName = $name;

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
        return BeneficiaryTypes::MERCHANT_ACCOUNT;
    }
}
