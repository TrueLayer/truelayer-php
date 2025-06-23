<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\SubMerchant;

use TrueLayer\Interfaces\ArrayableInterface;
use TrueLayer\Interfaces\HasAttributesInterface;

interface PaymentSubMerchantsInterface extends ArrayableInterface, HasAttributesInterface
{
    /**
     * @return UltimateCounterpartyInterface|null
     */
    public function getUltimateCounterparty(): ?UltimateCounterpartyInterface;

    /**
     * @param UltimateCounterpartyInterface|null $ultimateCounterparty
     *
     * @return UltimateCounterpartyInterface
     */
    public function ultimateCounterparty(?UltimateCounterpartyInterface $ultimateCounterparty): UltimateCounterpartyInterface;
}