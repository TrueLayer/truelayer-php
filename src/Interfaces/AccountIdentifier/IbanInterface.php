<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\AccountIdentifier;

interface IbanInterface extends IbanDetailsInterface
{
    /**
     * @param string $iban
     *
     * @return IbanInterface
     */
    public function iban(string $iban): IbanInterface;
}
