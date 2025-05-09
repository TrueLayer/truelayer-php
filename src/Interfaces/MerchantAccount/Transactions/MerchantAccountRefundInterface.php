<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\MerchantAccount\Transactions;

use TrueLayer\Interfaces\Payout\Beneficiary\PaymentSourceBeneficiaryInterface;

interface MerchantAccountRefundInterface
{
    /**
     * @return \DateTimeInterface
     */
    public function getCreatedAt(): \DateTimeInterface;

    /**
     * @return \DateTimeInterface|null
     */
    public function getExecutedAt(): ?\DateTimeInterface;

    /**
     * @return PaymentSourceBeneficiaryInterface
     */
    public function getBeneficiary(): PaymentSourceBeneficiaryInterface;

    /**
     * @return string
     */
    public function getContextCode(): string;

    /**
     * @return string
     */
    public function getRefundId(): string;

    /**
     * @return string
     */
    public function getPaymentId(): string;

    /**
     * @return string|null
     */
    public function getReturnedBy(): ?string;

    /**
     * @return string|null
     */
    public function getSchemeId(): ?string;

    /**
     * @return array<string, string>
     */
    public function getMetadata(): array;
}
