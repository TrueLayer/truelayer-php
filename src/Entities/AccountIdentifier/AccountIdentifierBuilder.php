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
     * @throws InvalidArgumentException
     *
     * @return ScanInterface
     */
    public function sortCodeAccountNumber(): ScanInterface
    {
        return $this->entityFactory->make(ScanInterface::class);
    }

    /**
     * @throws InvalidArgumentException
     *
     * @return IbanInterface
     */
    public function iban(): IbanInterface
    {
        return $this->entityFactory->make(IbanInterface::class);
    }

    /**
     * @param mixed[] $data
     *
     * @throws InvalidArgumentException
     *
     * @return AccountIdentifierInterface
     */
    public function fill(array $data): AccountIdentifierInterface
    {
        return $this->entityFactory->make(AccountIdentifierInterface::class, $data);
    }
}
