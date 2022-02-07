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
     * @var string
     */
    protected string $id;

    /**
     * @var AccountIdentifierInterface[]
     */
    protected array $accountIdentifiers;

    /**
     * @var string
     */
    protected string $accountHolderName;

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
        'id',
        'account_identifiers',
        'account_holder_name',
    ];

    /**
     * @return mixed[]
     */
    protected function rules(): array
    {
        return [
            'id' => 'nullable|string',
            'account_holder_name' => 'nullable|string',
            'account_identifiers' => 'nullable|array',
            'account_identifiers.*' => ValidType::of(AccountIdentifierInterface::class),
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
     * @return AccountIdentifierInterface[]
     */
    public function getAccountIdentifiers(): array
    {
        return $this->accountIdentifiers ?? [];
    }

    /**
     * @return string|null
     */
    public function getAccountHolderName(): ?string
    {
        return $this->accountHolderName ?? null;
    }
}
