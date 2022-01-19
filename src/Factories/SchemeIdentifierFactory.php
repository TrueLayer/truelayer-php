<?php

declare(strict_types=1);

namespace TrueLayer\Factories;

use TrueLayer\Constants\ExternalAccountTypes;
use TrueLayer\Contracts\SchemeIdentifier\SchemeIdentifierInterface;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Models\SchemeIdentifier\Bban;
use TrueLayer\Models\SchemeIdentifier\Iban;
use TrueLayer\Models\SchemeIdentifier\Nrb;
use TrueLayer\Models\SchemeIdentifier\Scan;
use TrueLayer\Services\Util\Type;
use TrueLayer\Traits\WithSdk;

final class SchemeIdentifierFactory
{
    use WithSdk;

    /**
     * @var string[]
     */
    private const TYPES = [
        ExternalAccountTypes::SORT_CODE_ACCOUNT_NUMBER => Scan::class,
        ExternalAccountTypes::IBAN => Iban::class,
        ExternalAccountTypes::BBAN => Bban::class,
        ExternalAccountTypes::NRB => Nrb::class,
    ];

    /**
     * @param array $schemeIdentifierData
     * @return SchemeIdentifierInterface
     * @throws InvalidArgumentException
     */
    public function makeFromArray(array $schemeIdentifierData): SchemeIdentifierInterface
    {
        $type = Type::getNullableString($schemeIdentifierData, 'type');

        if (!isset(self::TYPES[$type])) {
            throw new InvalidArgumentException('Unknown scheme identifier type');
        }

        return (self::TYPES[$type])::make($this->getSdk())->fill($schemeIdentifierData);
    }

    /**
     * @param array $schemeIdentifiersData
     * @return array
     * @throws InvalidArgumentException
     */
    public function makeManyFromArray(array $schemeIdentifiersData): array
    {
        return \array_map(function ($data) {
            if (!\is_array($data)) {
                throw new InvalidArgumentException('Scheme identifiers should be array.');
            }

            return $this->makeFromArray($data);
        }, $schemeIdentifiersData);
    }
}
