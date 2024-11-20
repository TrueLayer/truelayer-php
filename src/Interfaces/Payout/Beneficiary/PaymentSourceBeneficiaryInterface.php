<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payout\Beneficiary;

interface PaymentSourceBeneficiaryInterface extends BeneficiaryInterface
{
    /**
     * @return string|null
     */
    public function getPaymentSourceId(): ?string;

    /**
     * @param string $id
     *
     * @return PaymentSourceBeneficiaryInterface
     */
    public function paymentSourceId(string $id): PaymentSourceBeneficiaryInterface;

    /**
     * @return string|null
     */
    public function getUserId(): ?string;

    /**
     * @param string $userId
     *
     * @return PaymentSourceBeneficiaryInterface
     */
    public function userId(string $userId): PaymentSourceBeneficiaryInterface;
}
