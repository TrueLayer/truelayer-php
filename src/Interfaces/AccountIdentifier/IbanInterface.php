<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\AccountIdentifier;

interface IbanInterface extends IbanDetailsInterface
{
    /**
     * @param string $iban
     * Valid International Bank Account Number (no spaces).
     * Consists of a 2 letter country code,
     * followed by 2 check digits,
     * and then by up to 30 alphanumeric characters (also known as the BBAN).
     * Pattern: ^[A-Z]{2}[0-9]{2}[A-Z0-9]{11,30}$
     *
     * @return IbanInterface
     */
    public function iban(string $iban): IbanInterface;
}
