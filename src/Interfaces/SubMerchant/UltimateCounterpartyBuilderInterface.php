<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\SubMerchant;

interface UltimateCounterpartyBuilderInterface
{
    /**
     * @return UltimateCounterpartyBusinessClientInterface
     */
    public function businessClient(): UltimateCounterpartyBusinessClientInterface;

    /**
     * @return UltimateCounterpartyBusinessDivisionInterface
     */
    public function businessDivision(): UltimateCounterpartyBusinessDivisionInterface;

    /**
     * @param mixed[] $data
     *
     * @return UltimateCounterpartyInterface
     */
    public function fill(array $data): UltimateCounterpartyInterface;
}