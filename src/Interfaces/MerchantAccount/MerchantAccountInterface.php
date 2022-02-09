<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\MerchantAccount;

use TrueLayer\Interfaces\AccountIdentifier\AccountIdentifierInterface;
use TrueLayer\Interfaces\ArrayableInterface;

interface MerchantAccountInterface extends ArrayableInterface
{
    /**
     * @return string|null
     */
    public function getId(): ?string;

    /**
     * @return string
     */
    public function getCurrency(): string;

    /**
     * @return AccountIdentifierInterface[]
     */
    public function getAccountIdentifiers(): array;

    /**
     * @return int
     */
    public function getAvailableBalanceInMinor(): int;

    /**
     * @return int
     */
    public function getCurrentBalanceInMinor(): int;

    /**
     * @return string
     */
    public function getAccountHolderName(): string;
}
