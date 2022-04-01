<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payout;

use TrueLayer\Interfaces\HasAttributesInterface;

interface PayoutCreatedInterface extends HasAttributesInterface
{
    /**
     * @return string
     */
    public function getId(): string;
}
