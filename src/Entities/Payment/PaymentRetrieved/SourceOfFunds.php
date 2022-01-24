<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payment\PaymentRetrieved;

use TrueLayer\Entities\Entity;
use TrueLayer\Interfaces\Payment\SourceOfFundsInterface;
use TrueLayer\Interfaces\SchemeIdentifier\SchemeIdentifierInterface;
use TrueLayer\Validation\ValidType;

final class SourceOfFunds extends Entity implements SourceOfFundsInterface
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
     * @var string[]
     */
    protected array $casts = [
        'scheme_identifiers.*' => SchemeIdentifierInterface::class,
    ];

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
}
