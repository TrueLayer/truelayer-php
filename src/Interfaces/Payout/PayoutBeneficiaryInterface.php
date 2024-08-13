<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payout;

use TrueLayer\Interfaces\ArrayableInterface;
use TrueLayer\Interfaces\HasAttributesInterface;

interface PayoutBeneficiaryInterface extends ArrayableInterface, HasAttributesInterface
{
    /**
     * @return string
     */
    public function getType(): string;
}
