<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Remitter;

interface RemitterAccountIdentifierBuilderInterface
{
    /**
     * @return ScanRemitterInterface
     */
    public function sortCodeAccountNumber(): ScanRemitterInterface;

    /**
     * @return IbanRemitterInterface
     */
    public function iban(): IbanRemitterInterface;

    /**
     * @return BbanRemitterInterface
     */
    public function bban(): BbanRemitterInterface;

    /*
     * @return NrbRemitterInterface
     */
    public function nrb(): NrbRemitterInterface;
}
