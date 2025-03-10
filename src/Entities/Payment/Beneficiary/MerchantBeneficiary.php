<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payment\Beneficiary;

use TrueLayer\Constants\BeneficiaryTypes;
use TrueLayer\Entities\Entity;
use TrueLayer\Interfaces\Payment\Beneficiary\MerchantBeneficiaryInterface;
use TrueLayer\Interfaces\Remitter\RemitterVerification\RemitterVerificationInterface;

final class MerchantBeneficiary extends Entity implements MerchantBeneficiaryInterface
{
    /**
     * @var string
     */
    protected string $merchantAccountId;

    /**
     * @var string|null
     */
    protected ?string $accountHolderName;

    /**
     * @var string|null
     */
    protected ?string $reference;

    /**
     * @var string
     */
    protected string $statementReference;

    /**
     * @var RemitterVerificationInterface
     */
    protected RemitterVerificationInterface $verification;

    /**
     * @var string[]
     */
    protected array $casts = [
        'verification' => RemitterVerificationInterface::class,
    ];

    /**
     * @var string[]
     */
    protected array $arrayFields = [
        'merchant_account_id',
        'account_holder_name',
        'reference',
        'statement_reference',
        'verification',
        'type',
    ];

    /**
     * @return string
     */
    public function getMerchantAccountId(): string
    {
        return $this->merchantAccountId;
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
     * @return string|null
     */
    public function getStatementReference(): ?string
    {
        return $this->statementReference ?? null;
    }

    /**
     * @param string $statementReference
     *
     * @return $this
     */
    public function statementReference(string $statementReference): self
    {
        $this->statementReference = $statementReference;

        return $this;
    }

    /**
     * @return RemitterVerificationInterface|null
     */
    public function getVerification(): ?RemitterVerificationInterface
    {
        return $this->verification ?? null;
    }

    /**
     * @param RemitterVerificationInterface $verification
     *
     * @return MerchantBeneficiary
     */
    public function verification(RemitterVerificationInterface $verification): MerchantBeneficiary
    {
        $this->verification = $verification;

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
