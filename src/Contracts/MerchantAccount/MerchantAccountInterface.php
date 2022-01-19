<?php

declare(strict_types=1);

namespace TrueLayer\Contracts\MerchantAccount;

use TrueLayer\Contracts\ArrayableInterface;
use TrueLayer\Contracts\SchemeIdentifier\SchemeIdentifierInterface;

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
     * @return SchemeIdentifierInterface[]
     */
    public function getSchemeIdentifiers(): array;

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
