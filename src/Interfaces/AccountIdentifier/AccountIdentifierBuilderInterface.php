<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\AccountIdentifier;

use TrueLayer\Exceptions\InvalidArgumentException;

interface AccountIdentifierBuilderInterface
{
    /**
     * @return ScanInterface
     * The scheme identifier for a bank account participating in UK payment schemes.
     */
    public function sortCodeAccountNumber(): ScanInterface;

    /**
     * @return IbanInterface
     */
    public function iban(): IbanInterface;

    /**
     * @param mixed[] $data
     *
     * @throws InvalidArgumentException
     *
     * @return AccountIdentifierInterface
     */
    public function fill(array $data): AccountIdentifierInterface;
}
