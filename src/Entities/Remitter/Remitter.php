<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Remitter;

use TrueLayer\Entities\Entity;
use TrueLayer\Interfaces\AccountIdentifier\AccountIdentifierInterface;
use TrueLayer\Interfaces\Remitter\RemitterInterface;

final class Remitter extends Entity implements RemitterInterface
{
    /**
     * @var string
     */
    protected string $accountHolderName;

    /**
     * @var AccountIdentifierInterface
     */
    protected AccountIdentifierInterface $accountIdentifier;

    /**
     * @var mixed[]
     */
    protected array $casts = [
        'account_identifier' => AccountIdentifierInterface::class,
    ];

    /**
     * @var string[]
     */
    protected array $arrayFields = [
        'account_holder_name',
        'account_identifier'
    ];


    /**
     * @return string|null
     */
    public function getAccountHolderName(): ?string
    {
        return $this->accountHolderName;
    }

    /**
     * @param string $accountHolderName
     * @return RemitterInterface
     */
    public function accountHolderName(string $accountHolderName): RemitterInterface
    {
        $this->accountHolderName = $accountHolderName;
        return $this;
    }

    /**
     * @return AccountIdentifierInterface|null
     */
    public function getAccountIdentifier(): ?AccountIdentifierInterface
    {
        return $this->accountIdentifier;
    }

    /**
     * @param AccountIdentifierInterface $accountIdentifier
     * @return RemitterInterface
     */
    public function accountIdentifier(AccountIdentifierInterface $accountIdentifier): RemitterInterface
    {
        $this->accountIdentifier = $accountIdentifier;
        return $this;
    }
}
