<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payment\SubMerchants;

use TrueLayer\Entities\Entity;
use TrueLayer\Interfaces\Payment\SubMerchants\PaymentSubMerchantsInterface;
use TrueLayer\Interfaces\Payment\SubMerchants\UltimateCounterpartyInterface;

final class PaymentSubMerchants extends Entity implements PaymentSubMerchantsInterface
{
    /**
     * @var UltimateCounterpartyInterface
     */
    protected UltimateCounterpartyInterface $ultimateCounterparty;

    /**
     * @var string[]
     */
    protected array $casts = [
        'ultimate_counterparty' => UltimateCounterpartyInterface::class,
    ];

    /**
     * @var string[]
     */
    protected array $arrayFields = [
        'ultimate_counterparty',
    ];

    /**
     * @return UltimateCounterpartyInterface|null
     */
    public function getUltimateCounterparty(): ?UltimateCounterpartyInterface
    {
        return $this->ultimateCounterparty ?? null;
    }

    /**
     * @param UltimateCounterpartyInterface $ultimateCounterparty
     *
     * @return PaymentSubMerchantsInterface
     */
    public function ultimateCounterparty(UltimateCounterpartyInterface $ultimateCounterparty): PaymentSubMerchantsInterface
    {
        $this->ultimateCounterparty = $ultimateCounterparty;

        return $this;
    }
}
