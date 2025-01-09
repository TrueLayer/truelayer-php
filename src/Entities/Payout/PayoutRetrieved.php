<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payout;

use TrueLayer\Entities\Entity;
use TrueLayer\Interfaces\Payout\Beneficiary\BeneficiaryInterface;
use TrueLayer\Interfaces\Payout\PayoutRetrievedInterface;

abstract class PayoutRetrieved extends Entity implements PayoutRetrievedInterface
{
    /**
     * @var string
     */
    protected string $id;

    /**
     * @var string
     */
    protected string $merchantAccountId;

    /**
     * @var int
     */
    protected int $amountInMinor;

    /**
     * @var string
     */
    protected string $currency;

    /**
     * @var BeneficiaryInterface
     */
    protected BeneficiaryInterface $beneficiary;

    /**
     * @var array<string, string>
     */
    protected array $metadata;

    /**
     * @var string
     */
    protected string $status;

    /**
     * @var \DateTimeInterface
     */
    protected \DateTimeInterface $createdAt;

    /**
     * @var string[]
     */
    protected array $casts = [
        'beneficiary' => BeneficiaryInterface::class,
        'created_at' => \DateTimeInterface::class,
    ];

    /**
     * @var string[]
     */
    protected array $arrayFields = [
        'id',
        'merchant_account_id',
        'amount_in_minor',
        'currency',
        'beneficiary',
        'metadata',
        'status',
        'created_at',
    ];

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getMerchantAccountId(): string
    {
        return $this->merchantAccountId;
    }

    /**
     * @return int
     */
    public function getAmountInMinor(): int
    {
        return $this->amountInMinor;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
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

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }
}
