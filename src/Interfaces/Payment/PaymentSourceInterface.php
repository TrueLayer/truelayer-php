<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payment;

use TrueLayer\Interfaces\ArrayableInterface;
use TrueLayer\Interfaces\AccountIdentifier\AccountIdentifierInterface;

interface PaymentSourceInterface extends ArrayableInterface
{
    /**
     * @return string|null
     */
    public function getId(): ?string;

    /**
     * @return AccountIdentifierInterface[]
     */
    public function getAccountIdentifiers(): array;

    /**
     * @return string|null
     */
    public function getAccountHolderName(): ?string;
}
