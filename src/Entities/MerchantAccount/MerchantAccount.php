<?php

declare(strict_types=1);

namespace TrueLayer\Entities\MerchantAccount;

use TrueLayer\Entities\Entity;
use TrueLayer\Interfaces\MerchantAccount\MerchantAccountInterface;
use TrueLayer\Interfaces\SchemeIdentifier\SchemeIdentifierInterface;
use TrueLayer\Validation\ValidType;

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
     * @var SchemeIdentifierInterface[]
     */
    protected array $schemeIdentifiers;

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
        'scheme_identifiers.*' => SchemeIdentifierInterface::class,
    ];

    /**
     * @var string[]
     */
    protected array $arrayFields = [
        'id',
        'currency',
        'scheme_identifiers',
        'available_balance_in_minor',
        'current_balance_in_minor',
        'account_holder_name',
    ];

    /**
     * @return mixed[]
     */
    protected function rules(): array
    {
        return [
            'id' => 'required|string',
            'currency' => ['required', 'string'],
            'scheme_identifiers' => 'required|array',
            'scheme_identifiers.*' => [ValidType::of(SchemeIdentifierInterface::class)],
            'available_balance_in_minor' => 'required|int',
            'current_balance_in_minor' => 'required|int',
            'account_holder_name' => 'required|string',
        ];
    }

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
     * @return SchemeIdentifierInterface[]
     */
    public function getSchemeIdentifiers(): array
    {
        return $this->schemeIdentifiers;
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
