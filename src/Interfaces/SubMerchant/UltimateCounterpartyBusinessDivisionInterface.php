<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\SubMerchant;

interface UltimateCounterpartyBusinessDivisionInterface extends UltimateCounterpartyInterface
{
    /**
     * @return string|null
     */
    public function getId(): ?string;

    /**
     * @param string $id
     *
     * @return UltimateCounterpartyBusinessDivisionInterface
     */
    public function id(string $id): UltimateCounterpartyBusinessDivisionInterface;

    /**
     * @return string|null
     */
    public function getName(): ?string;

    /**
     * @param string $name
     *
     * @return UltimateCounterpartyBusinessDivisionInterface
     */
    public function name(string $name): UltimateCounterpartyBusinessDivisionInterface;
}