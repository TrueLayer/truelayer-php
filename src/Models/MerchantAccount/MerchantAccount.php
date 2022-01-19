<?php

declare(strict_types=1);

namespace TrueLayer\Models\MerchantAccount;

use TrueLayer\Constants\Currencies;
use TrueLayer\Contracts\MerchantAccount\MerchantAccountInterface;
use TrueLayer\Contracts\SchemeIdentifier\SchemeIdentifierInterface;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Exceptions\ValidationException;
use TrueLayer\Factories\SchemeIdentifierFactory;
use TrueLayer\Models\Model;
use TrueLayer\Services\Util\Type;
use TrueLayer\Validation\AllowedConstant;
use TrueLayer\Validation\ValidType;

final class MerchantAccount extends Model implements MerchantAccountInterface
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

    /**
     * @param array $data
     * @return $this
     * @throws ValidationException
     * @throws InvalidArgumentException
     */
    public function fill(array $data): self
    {
        if ($schemeIdentifiers = Type::getNullableArray($data, 'scheme_identifiers')) {
            $data['scheme_identifiers'] = SchemeIdentifierFactory::make($this->getSdk())
                ->makeManyFromArray($schemeIdentifiers);
        }

        return parent::fill($data);
    }
}
