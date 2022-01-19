<?php

declare(strict_types=1);

namespace TrueLayer\Models\Payment\PaymentRetrieved;

use TrueLayer\Contracts\Payment\SourceOfFundsInterface;
use TrueLayer\Contracts\SchemeIdentifier\SchemeIdentifierInterface;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Exceptions\ValidationException;
use TrueLayer\Factories\SchemeIdentifierFactory;
use TrueLayer\Models\Model;
use TrueLayer\Services\Util\Type;
use TrueLayer\Validation\ValidType;

final class SourceOfFunds extends Model implements SourceOfFundsInterface
{
    /**
     * @var SchemeIdentifierInterface[]
     */
    private array $schemeIdentifiers;

    /**
     * @var string
     */
    private string $externalAccountId;

    /**
     * @var string
     */
    private string $accountHolderName;

    /**
     * @var array|string[]
     */
    protected array $arrayFields = [
        'scheme_identifiers',
        'external_account_id',
        'account_holder_name',
    ];

    /**
     * @return mixed[]
     */
    protected function rules(): array
    {
        return [
            'external_account_id' => 'nullable|string',
            'account_holder_name' => 'nullable|string',
            'scheme_identifiers' => 'array',
            'scheme_identifiers.*' => ValidType::of(SchemeIdentifierInterface::class),
        ];
    }

    /**
     * @return SchemeIdentifierInterface[]
     */
    public function getSchemeIdentifiers(): array
    {
        return $this->schemeIdentifiers ?? [];
    }

    /**
     * @param SchemeIdentifierInterface[] $schemeIdentifiers
     *
     * @return SourceOfFundsInterface
     */
    public function schemeIdentifiers(array $schemeIdentifiers): SourceOfFundsInterface
    {
        $this->schemeIdentifiers = $schemeIdentifiers;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getExternalAccountId(): ?string
    {
        return $this->externalAccountId ?? null;
    }

    /**
     * @param string $externalAccountId
     *
     * @return SourceOfFundsInterface
     */
    public function externalAccountId(string $externalAccountId): SourceOfFundsInterface
    {
        $this->externalAccountId = $externalAccountId;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAccountHolderName(): ?string
    {
        return $this->accountHolderName ?? null;
    }

    /**
     * @param string $accountHolderName
     *
     * @return SourceOfFundsInterface
     */
    public function accountHolderName(string $accountHolderName): SourceOfFundsInterface
    {
        $this->accountHolderName = $accountHolderName;

        return $this;
    }

    /**
     * @param mixed[] $data
     *
     * @throws InvalidArgumentException
     * @throws ValidationException
     *
     * @return $this
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
