<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payment;

use TrueLayer\Interfaces\ArrayableInterface;
use TrueLayer\Interfaces\SchemeIdentifier\SchemeIdentifierInterface;

interface SourceOfFundsInterface extends ArrayableInterface
{
    /**
     * @return SchemeIdentifierInterface[]
     */
    public function getSchemeIdentifiers(): array;

    /**
     * @return string|null
     */
    public function getExternalAccountId(): ?string;

    /**
     * @return string|null
     */
    public function getAccountHolderName(): ?string;
}
