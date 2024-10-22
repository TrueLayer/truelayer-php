<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payment\Beneficiary;

use TrueLayer\Interfaces\ArrayableInterface;
use TrueLayer\Interfaces\HasAttributesInterface;

interface BeneficiaryInterface extends ArrayableInterface, HasAttributesInterface
{
    /**
     * @return string
     */
    public function getType(): string;
}
