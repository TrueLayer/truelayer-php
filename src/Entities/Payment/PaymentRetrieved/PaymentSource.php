<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payment\PaymentRetrieved;

use TrueLayer\Entities\Entity;
use TrueLayer\Interfaces\Payment\PaymentSourceInterface;
use TrueLayer\Interfaces\AccountIdentifier\AccountIdentifierInterface;
use TrueLayer\Validation\ValidType;

final class PaymentSource extends Entity implements PaymentSourceInterface
{
    /**
     * @var AccountIdentifierInterface[]
     */
    private array $accountIdentifiers;

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
        'account_identifiers.*' => AccountIdentifierInterface::class,
    ];

    /**
     * @var array|string[]
     */
    protected array $arrayFields = [
        'account_identifiers',
        'external_account_id',
        'account_holder_name',
    ];

    /**
     * @return mixed[]
     */
    protected function rules(): array
    {
        return [
            'id' => 'required|string',
            'details' => 'array',
            'external_account_id' => 'nullable|string',
            'account_holder_name' => 'nullable|string',
            'account_identifiers' => 'array',
            'account_identifiers.*' => ValidType::of(AccountIdentifierInterface::class),
        ];
    }

    /**
     * @return AccountIdentifierInterface[]
     */
    public function getAccountIdentifiers(): array
    {
        return $this->accountIdentifiers ?? [];
    }

    /**
     * @param AccountIdentifierInterface[] $accountIdentifiers
     *
     * @return PaymentSourceInterface
     */
    public function accountIdentifiers(array $accountIdentifiers): PaymentSourceInterface
    {
        $this->accountIdentifiers = $accountIdentifiers;

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
     * @return PaymentSourceInterface
     */
    public function externalAccountId(string $externalAccountId): PaymentSourceInterface
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
     * @return PaymentSourceInterface
     */
    public function accountHolderName(string $accountHolderName): PaymentSourceInterface
    {
        $this->accountHolderName = $accountHolderName;

        return $this;
    }
}
