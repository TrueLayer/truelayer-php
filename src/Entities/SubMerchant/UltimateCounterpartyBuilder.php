<?php

declare(strict_types=1);

namespace TrueLayer\Entities\SubMerchant;

use TrueLayer\Entities\EntityBuilder;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Interfaces\SubMerchant\UltimateCounterpartyBuilderInterface;
use TrueLayer\Interfaces\SubMerchant\UltimateCounterpartyBusinessClientInterface;
use TrueLayer\Interfaces\SubMerchant\UltimateCounterpartyBusinessDivisionInterface;
use TrueLayer\Interfaces\SubMerchant\UltimateCounterpartyInterface;

final class UltimateCounterpartyBuilder extends EntityBuilder implements UltimateCounterpartyBuilderInterface
{
    /**
     * @throws InvalidArgumentException
     *
     * @return UltimateCounterpartyBusinessClientInterface
     */
    public function businessClient(): UltimateCounterpartyBusinessClientInterface
    {
        return $this->entityFactory->make(UltimateCounterpartyBusinessClientInterface::class);
    }

    /**
     * @throws InvalidArgumentException
     *
     * @return UltimateCounterpartyBusinessDivisionInterface
     */
    public function businessDivision(): UltimateCounterpartyBusinessDivisionInterface
    {
        return $this->entityFactory->make(UltimateCounterpartyBusinessDivisionInterface::class);
    }

    /**
     * @param mixed[] $data
     *
     * @throws InvalidArgumentException
     *
     * @return UltimateCounterpartyInterface
     */
    public function fill(array $data): UltimateCounterpartyInterface
    {
        return $this->entityFactory->make(UltimateCounterpartyInterface::class, $data);
    }
}