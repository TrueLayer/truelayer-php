<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payment;

use TrueLayer\Interfaces\ArrayableInterface;

interface PaymentUserRetrievedInterface extends ArrayableInterface
{
 /**
     * @return string|null
     */
    public function getId(): ?string;

}