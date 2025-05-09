<?php

declare(strict_types=1);

namespace TrueLayer\Entities\MerchantAccount\Transactions;

use TrueLayer\Interfaces\MerchantAccount\Transactions\MerchantAccountRefundInterface;
use TrueLayer\Interfaces\Payout\Beneficiary\PaymentSourceBeneficiaryInterface;

class MerchantAccountRefund extends MerchantAccountTransactionRetrieved implements MerchantAccountRefundInterface
{
    /**
     * @var \DateTimeInterface
     */
    protected \DateTimeInterface $createdAt;

    /**
     * @var \DateTimeInterface
     */
    protected \DateTimeInterface $executedAt;

    /**
     * @var PaymentSourceBeneficiaryInterface
     */
    protected PaymentSourceBeneficiaryInterface $beneficiary;

    /**
     * @var string
     */
    protected string $contextCode;

    /**
     * @var string
     */
    protected string $refundId;

    /**
     * @var string
     */
    protected string $paymentId;

    /**
     * @var string
     */
    protected string $returnedBy;

    /**
     * @var string
     */
    protected string $schemeId;

    /**
     * @var array<string, string>
     */
    protected array $metadata;

    /**
     * @var array|string[]
     */
    protected array $casts = [
        'created_at' => \DateTimeInterface::class,
        'executed_at' => \DateTimeInterface::class,
        'beneficiary' => PaymentSourceBeneficiaryInterface::class,
    ];

    /**
     * @return string[]
     */
    protected function arrayFields(): array
    {
        return \array_merge(parent::arrayFields(), [
            'created_at',
            'executed_at',
            'beneficiary',
            'context_code',
            'refund_id',
            'payment_id',
            'returned_by',
            'scheme_id',
            'metadata',
        ]);
    }

    /**
     * @return \DateTimeInterface
     */
    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getExecutedAt(): ?\DateTimeInterface
    {
        return $this->executedAt ?? null;
    }

    /**
     * @return PaymentSourceBeneficiaryInterface
     */
    public function getBeneficiary(): PaymentSourceBeneficiaryInterface
    {
        return $this->beneficiary;
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
    public function getRefundId(): string
    {
        return $this->refundId;
    }

    /**
     * @return string
     */
    public function getPaymentId(): string
    {
        return $this->paymentId;
    }

    /**
     * @return string|null
     */
    public function getReturnedBy(): ?string
    {
        return $this->returnedBy ?? null;
    }

    /**
     * @return string|null
     */
    public function getSchemeId(): ?string
    {
        return $this->schemeId ?? null;
    }

    /**
     * @return array<string, string>
     */
    public function getMetadata(): array
    {
        return $this->metadata ?? [];
    }
}
