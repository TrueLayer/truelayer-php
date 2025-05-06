<?php

declare(strict_types=1);

namespace TrueLayer\Entities\MerchantAccount\Transactions;

use TrueLayer\Interfaces\MerchantAccount\Transactions\MerchantAccountPaymentInterface;
use TrueLayer\Interfaces\Payout\Beneficiary\PaymentSourceBeneficiaryInterface;

class MerchantAccountPayment extends MerchantAccountTransactionRetrieved implements MerchantAccountPaymentInterface
{
    /**
     * @var \DateTimeInterface
     */
    protected \DateTimeInterface $settledAt;

    /**
     * @var PaymentSourceBeneficiaryInterface
     */
    protected PaymentSourceBeneficiaryInterface $paymentSource;

    /**
     * @var string
     */
    protected string $paymentId;

    /**
     * @var array<string, string>
     */
    protected array $metadata;

    /**
     * @return \DateTimeInterface
     */
    public function getSettledAt(): \DateTimeInterface
    {
        return $this->settledAt;
    }

    /**
     * @return PaymentSourceBeneficiaryInterface
     */
    public function getPaymentSource(): PaymentSourceBeneficiaryInterface
    {
        return $this->paymentSource;
    }

    /**
     * @return string
     */
    public function getPaymentId(): string
    {
        return $this->paymentId;
    }

    /**
     * @return string[]
     */
    public function getMetadata(): array
    {
        return $this->metadata ?? [];
    }
}
