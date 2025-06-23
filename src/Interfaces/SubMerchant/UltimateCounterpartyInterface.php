<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\SubMerchant;

use TrueLayer\Interfaces\ArrayableInterface;
use TrueLayer\Interfaces\HasAttributesInterface;

interface UltimateCounterpartyInterface extends ArrayableInterface, HasAttributesInterface
{
    /**
     * @return string|null
     */
    public function getType(): ?string;

    /**
     * @param string $type
     *
     * @return UltimateCounterpartyInterface
     */
    public function type(string $type): UltimateCounterpartyInterface;
}