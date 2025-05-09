<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\MerchantAccount\Transactions;

use TrueLayer\Interfaces\Payment\PaymentSourceInterface;
use TrueLayer\Interfaces\Payout\Beneficiary\PaymentSourceBeneficiaryInterface;

interface MerchantAccountPaymentInterface
{
    /**
     * @return \DateTimeInterface
     */
    public function getSettledAt(): \DateTimeInterface;

    /**
     * @return PaymentSourceBeneficiaryInterface
     */
    public function getPaymentSource(): PaymentSourceBeneficiaryInterface;

    /**
     * @return string
     */
    public function getPaymentId(): string;

    /**
     * @return array<string, string>
     */
    public function getMetadata(): array;
}
