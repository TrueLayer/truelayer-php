<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payment\SubMerchants;

interface PaymentSubMerchantsInterface
{
    /**
     * @return UltimateCounterpartyInterface|null
     */
    public function getUltimateCounterparty(): ?UltimateCounterpartyInterface;

    /**
     * @param UltimateCounterpartyInterface $ultimateCounterparty
     *
     * @return PaymentSubMerchantsInterface
     */
    public function ultimateCounterparty(UltimateCounterpartyInterface $ultimateCounterparty): PaymentSubMerchantsInterface;
}
