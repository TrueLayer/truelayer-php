<?php

declare(strict_types=1);

namespace TrueLayer\Entities\AccountIdentifier;

use TrueLayer\Entities\EntityBuilder;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Interfaces\AccountIdentifier\AccountIdentifierBuilderInterface;
use TrueLayer\Interfaces\AccountIdentifier\AccountIdentifierInterface;
use TrueLayer\Interfaces\AccountIdentifier\IbanInterface;
use TrueLayer\Interfaces\AccountIdentifier\ScanInterface;

final class AccountIdentifierBuilder extends EntityBuilder implements AccountIdentifierBuilderInterface
{
    /**
     * @return ScanInterface
     * @throws InvalidArgumentException
     *
     */
    public function sortCodeAccountNumber(): ScanInterface
    {
        return $this->entityFactory->make(ScanInterface::class);
    }

    /**
     * @return IbanInterface
     * @throws InvalidArgumentException
     *
     */
    public function iban(): IbanInterface
    {
        return $this->entityFactory->make(IbanInterface::class);
    }

    /**
     * @param mixed[] $data
     *
     * @return AccountIdentifierInterface
     * @throws InvalidArgumentException
     *
     */
    public function fill(array $data): AccountIdentifierInterface
    {
        return $this->entityFactory->make(AccountIdentifierInterface::class, $data);
    }
}
