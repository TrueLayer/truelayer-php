<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payment;

use TrueLayer\Interfaces\AccountIdentifier\AccountIdentifierInterface;
use TrueLayer\Interfaces\ArrayableInterface;

/**
 * Information about the source of funds, if available from the remitter's bank. Account identifiers will
 * be those provided by the bank.
 */
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
