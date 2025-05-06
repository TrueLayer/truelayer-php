<?php

declare(strict_types=1);

namespace TrueLayer\Entities\MerchantAccount\Transactions;

use TrueLayer\Interfaces\MerchantAccount\Transactions\MerchantAccountPayoutInterface;
use TrueLayer\Interfaces\Payout\Beneficiary\BeneficiaryInterface;

abstract class MerchantAccountPayout extends MerchantAccountTransactionRetrieved implements MerchantAccountPayoutInterface
{
    /**
     * @var \DateTimeInterface
     */
    protected \DateTimeInterface $createdAt;

    /**
     * @var string
     */
    protected string $contextCode;

    /**
     * @var string
     */
    protected string $payoutId;

    /**
     * @var BeneficiaryInterface
     */
    protected BeneficiaryInterface $beneficiary;

    /**
     * @var array<string, string>
     */
    protected array $metadata;

    /**
     * @return \DateTimeInterface
     */
    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @return string
     */
    public function getContextCode(): string
    {
        return $this->contextCode;
    }

    /**
     * @return string
     */
    public function getPayoutId(): string
    {
        return $this->payoutId;
    }

    /**
     * @return BeneficiaryInterface
     */
    public function getBeneficiary(): BeneficiaryInterface
    {
        return $this->beneficiary;
    }

    /**
     * @return array<string, string>
     */
    public function getMetadata(): array
    {
        return $this->metadata ?? [];
    }
}
