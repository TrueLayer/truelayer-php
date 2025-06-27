<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\SubMerchant;

use TrueLayer\Interfaces\ArrayableInterface;
use TrueLayer\Interfaces\HasAttributesInterface;

interface PayoutSubMerchantsInterface extends ArrayableInterface, HasAttributesInterface
{
    /**
     * @return UltimateCounterpartyBusinessClientInterface|null
     */
    public function getUltimateCounterparty(): ?UltimateCounterpartyBusinessClientInterface;

    /**
     * @param UltimateCounterpartyBusinessClientInterface|null $ultimateCounterparty
     *
     * @return PayoutSubMerchantsInterface
     */
    public function ultimateCounterparty(?UltimateCounterpartyBusinessClientInterface $ultimateCounterparty): PayoutSubMerchantsInterface;
}