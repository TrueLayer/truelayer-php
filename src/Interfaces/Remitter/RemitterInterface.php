<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Remitter;

use TrueLayer\Interfaces\AccountIdentifier\AccountIdentifierInterface;

interface RemitterInterface
{
    /**
     * The account holder name of the remitter.
     *
     * @return string|null
     */
    public function getAccountHolderName(): ?string;

    /**
     * @param string $accountHolderName
     *
     * @return RemitterInterface
     */
    public function accountHolderName(string $accountHolderName): RemitterInterface;

    /**
     * @return AccountIdentifierInterface|null
     */
    public function getAccountIdentifier(): ?AccountIdentifierInterface;

    /**
     * @param AccountIdentifierInterface $accountIdentifier
     *
     * @return RemitterInterface
     */
    public function accountIdentifier(AccountIdentifierInterface $accountIdentifier): RemitterInterface;
}
