<?php

declare(strict_types=1);

namespace TrueLayer\Entities\MerchantAccount;

use TrueLayer\Entities\Entity;
use TrueLayer\Interfaces\AccountIdentifier\AccountIdentifierInterface;
use TrueLayer\Interfaces\MerchantAccount\MerchantAccountInterface;

final class MerchantAccount extends Entity implements MerchantAccountInterface
{
    /**
     * @var string
     */
    protected string $id;

    /**
     * @var string
     */
    protected string $currency;

    /**
     * @var AccountIdentifierInterface[]
     */
    protected array $accountIdentifiers;

    /**
     * @var int
     */
    protected int $availableBalanceInMinor;

    /**
     * @var int
     */
    protected int $currentBalanceInMinor;

    /**
     * @var string
     */
    protected string $accountHolderName;

    protected array $casts = [
        'account_identifiers.*' => AccountIdentifierInterface::class,
    ];

    /**
     * @var string[]
     */
    protected array $arrayFields = [
        'id',
        'currency',
        'account_identifiers',
        'available_balance_in_minor',
        'current_balance_in_minor',
        'account_holder_name',
    ];

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id ?? null;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @return AccountIdentifierInterface[]
     */
    public function getAccountIdentifiers(): array
    {
        return $this->accountIdentifiers;
    }

    /**
     * @return int
     */
    public function getAvailableBalanceInMinor(): int
    {
        return $this->availableBalanceInMinor;
    }

    /**
     * @return int
     */
    public function getCurrentBalanceInMinor(): int
    {
        return $this->currentBalanceInMinor;
    }

    /**
     * @return string
     */
    public function getAccountHolderName(): string
    {
        return $this->accountHolderName;
    }
}
