<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payment\SubMerchants;

use TrueLayer\Interfaces\ArrayableInterface;
use TrueLayer\Interfaces\HasAttributesInterface;

interface UltimateCounterpartyInterface extends ArrayableInterface, HasAttributesInterface
{
    /**
     * @return string
     */
    public function getType(): string;
}
