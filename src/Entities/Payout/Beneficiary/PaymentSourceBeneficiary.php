<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payout\Beneficiary;

use TrueLayer\Constants\BeneficiaryTypes;
use TrueLayer\Entities\Entity;
use TrueLayer\Interfaces\Payout\Beneficiary\PaymentSourceBeneficiaryInterface;

final class PaymentSourceBeneficiary extends Entity implements PaymentSourceBeneficiaryInterface
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
     * @var string
     */
    protected string $reference;

    /**
     * @var string[]
     */
    protected array $arrayFields = [
        'payment_source_id',
        'user_id',
        'reference',
        'type',
    ];

    /**
     * @return string|null
     */
    public function getPaymentSourceId(): ?string
    {
        return $this->paymentSourceId ?? null;
    }

    /**
     * @param string $id
     *
     * @return PaymentSourceBeneficiaryInterface
     */
    public function paymentSourceId(string $id): PaymentSourceBeneficiaryInterface
    {
        $this->paymentSourceId = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return BeneficiaryTypes::PAYMENT_SOURCE;
    }

    /**
     * @return string|null
     */
    public function getUserId(): ?string
    {
        return $this->userId ?? null;
    }

    /**
     * @param string $userId
     *
     * @return PaymentSourceBeneficiaryInterface
     */
    public function userId(string $userId): PaymentSourceBeneficiaryInterface
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * @return string
     */
    public function getReference(): string
    {
        return $this->reference;
    }

    /**
     * @param string $reference
     *
     * @return PaymentSourceBeneficiaryInterface
     */
    public function reference(string $reference): PaymentSourceBeneficiaryInterface
    {
        $this->reference = $reference;

        return $this;
    }
}
