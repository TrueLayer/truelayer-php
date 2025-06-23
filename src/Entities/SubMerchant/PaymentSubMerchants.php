<?php

declare(strict_types=1);

namespace TrueLayer\Entities\SubMerchant;

use TrueLayer\Entities\Entity;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Interfaces\SubMerchant\PaymentSubMerchantsInterface;
use TrueLayer\Interfaces\SubMerchant\UltimateCounterpartyInterface;

final class PaymentSubMerchants extends Entity implements PaymentSubMerchantsInterface
{
    /**
     * @var UltimateCounterpartyInterface
     */
    protected UltimateCounterpartyInterface $ultimateCounterparty;

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
        'ultimate_counterparty' => UltimateCounterpartyInterface::class,
    ];

    /**
     * @return UltimateCounterpartyInterface|null
     */
    public function getUltimateCounterparty(): ?UltimateCounterpartyInterface
    {
        return $this->ultimateCounterparty ?? null;
    }

    /**
     * @param UltimateCounterpartyInterface|null $ultimateCounterparty
     *
     * @throws InvalidArgumentException
     *
     * @return UltimateCounterpartyInterface
     */
    public function ultimateCounterparty(?UltimateCounterpartyInterface $ultimateCounterparty = null): UltimateCounterpartyInterface
    {
        $this->ultimateCounterparty = $ultimateCounterparty ?: $this->entityFactory->make(UltimateCounterpartyInterface::class);

        return $this->ultimateCounterparty;
    }
}