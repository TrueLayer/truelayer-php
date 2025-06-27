<?php

declare(strict_types=1);

namespace TrueLayer\Entities\SubMerchant;

use TrueLayer\Entities\Entity;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Interfaces\SubMerchant\PayoutSubMerchantsInterface;
use TrueLayer\Interfaces\SubMerchant\UltimateCounterpartyBusinessClientInterface;

final class PayoutSubMerchants extends Entity implements PayoutSubMerchantsInterface
{
    /**
     * @var UltimateCounterpartyBusinessClientInterface
     */
    protected UltimateCounterpartyBusinessClientInterface $ultimateCounterparty;

    /**
     * @var string[]
     */
    protected array $arrayFields = [
        'ultimate_counterparty',
    ];

    /**
     * @var string[]
     */
    protected array $casts = [
        'ultimate_counterparty' => UltimateCounterpartyBusinessClientInterface::class,
    ];

    /**
     * @return UltimateCounterpartyBusinessClientInterface|null
     */
    public function getUltimateCounterparty(): ?UltimateCounterpartyBusinessClientInterface
    {
        return $this->ultimateCounterparty ?? null;
    }

    /**
     * @param UltimateCounterpartyBusinessClientInterface|null $ultimateCounterparty
     *
     * @throws InvalidArgumentException
     *
     * @return PayoutSubMerchantsInterface
     */
    public function ultimateCounterparty(?UltimateCounterpartyBusinessClientInterface $ultimateCounterparty = null): PayoutSubMerchantsInterface
    {
        $this->ultimateCounterparty = $ultimateCounterparty ?: $this->entityFactory->make(UltimateCounterpartyBusinessClientInterface::class);

        return $this;
    }
}