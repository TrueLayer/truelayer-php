<?php

declare(strict_types=1);

namespace TrueLayer\Models\Payment\PaymentRetrieved;

use TrueLayer\Constants\ExternalAccountTypes;
use TrueLayer\Contracts\Payment\SourceOfFundsInterface;
use TrueLayer\Contracts\SchemeIdentifier\SchemeIdentifierInterface;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Exceptions\ValidationException;
use TrueLayer\Models\Model;
use TrueLayer\Models\SchemeIdentifier\Bban;
use TrueLayer\Models\SchemeIdentifier\Iban;
use TrueLayer\Models\SchemeIdentifier\Nrb;
use TrueLayer\Models\SchemeIdentifier\Scan;
use TrueLayer\Services\Util\Type;
use TrueLayer\Validation\ValidType;

final class SourceOfFunds extends Model implements SourceOfFundsInterface
{
    /**
     * @var string[]
     */
    private array $schemeIdentifierTypes = [
        ExternalAccountTypes::SORT_CODE_ACCOUNT_NUMBER => Scan::class,
        ExternalAccountTypes::IBAN => Iban::class,
        ExternalAccountTypes::BBAN => Bban::class,
        ExternalAccountTypes::NRB => Nrb::class,
    ];

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
            'scheme_identifiers.*' => ValidType::of(Scan::class, Iban::class, Bban::class, Nrb::class),
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
            $data['scheme_identifiers'] = \array_map(function ($data) {
                if (!\is_array($data)) {
                    throw new InvalidArgumentException('Scheme identifiers should be array.');
                }

                $type = Type::getNullableString($data, 'type');

                if (!isset($this->schemeIdentifierTypes[$type])) {
                    throw new InvalidArgumentException('Unknown scheme identifier type');
                }

                return $this->schemeIdentifierTypes[$type]::make($this->getSdk())->fill($data);
            }, $schemeIdentifiers);
        }

        return parent::fill($data);
    }
}
