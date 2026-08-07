<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payment;

use TrueLayer\Interfaces\HasAttributesInterface;

/**
 * The newly created refund, as returned by the refund creation response.
 */
interface RefundCreatedInterface extends HasAttributesInterface
{
    /**
     * The unique ID of the refund.
     *
     * @return string
     */
    public function getId(): string;
}
