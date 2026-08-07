<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payment\SubMerchants;

interface UltimateCounterpartyBuilderInterface
{
    /**
     * @return BusinessDivisionUltimateCounterpartyInterface
     */
    public function businessDivision(): BusinessDivisionUltimateCounterpartyInterface;

    /**
     * @return BusinessClientUltimateCounterpartyInterface
     */
    public function businessClient(): BusinessClientUltimateCounterpartyInterface;
}
