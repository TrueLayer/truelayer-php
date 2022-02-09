<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\PaymentMethod;

use TrueLayer\Interfaces\ArrayableInterface;
use TrueLayer\Interfaces\HasAttributesInterface;

interface PaymentMethodInterface extends ArrayableInterface, HasAttributesInterface
{
    /**
     * @return string
     */
    public function getType(): string;
}
