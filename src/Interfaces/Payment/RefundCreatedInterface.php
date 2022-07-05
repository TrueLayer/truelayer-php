<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payment;

use TrueLayer\Interfaces\HasAttributesInterface;

interface RefundCreatedInterface extends HasAttributesInterface
{
    /**
     * @return string
     */
    public function getId(): string;
}
