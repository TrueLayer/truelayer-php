<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payment\SubMerchants;

use TrueLayer\Entities\EntityBuilder;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Interfaces\Payment\SubMerchants\BusinessClientUltimateCounterpartyInterface;
use TrueLayer\Interfaces\Payment\SubMerchants\BusinessDivisionUltimateCounterpartyInterface;
use TrueLayer\Interfaces\Payment\SubMerchants\UltimateCounterpartyBuilderInterface;

final class UltimateCounterpartyBuilder extends EntityBuilder implements UltimateCounterpartyBuilderInterface
{
    /**
     * @throws InvalidArgumentException
     *
     * @return BusinessDivisionUltimateCounterpartyInterface
     */
    public function businessDivision(): BusinessDivisionUltimateCounterpartyInterface
    {
        return $this->entityFactory->make(BusinessDivisionUltimateCounterpartyInterface::class);
    }

    /**
     * @throws InvalidArgumentException
     *
     * @return BusinessClientUltimateCounterpartyInterface
     */
    public function businessClient(): BusinessClientUltimateCounterpartyInterface
    {
        return $this->entityFactory->make(BusinessClientUltimateCounterpartyInterface::class);
    }
}
