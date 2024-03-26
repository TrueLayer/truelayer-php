<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Webhook\Beneficiary;

use TrueLayer\Interfaces\Webhook\Beneficiary\PaymentSourceBeneficiaryInterface;

class PaymentSourceBeneficiary extends Beneficiary implements PaymentSourceBeneficiaryInterface
{
    /**
     * @var string
     */
    protected string $paymentSourceId;

    /**
     * @var string
     */
    protected string $userId;

    /**
     * @return mixed[]
     */
    protected function arrayFields(): array
    {
        return \array_merge(parent::arrayFields(), [
            'payment_source_id',
            'user_id',
        ]);
    }

    /**
     * @return string
     */
    public function getPaymentSourceId(): string
    {
        return $this->paymentSourceId;
    }

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->userId;
    }
}
