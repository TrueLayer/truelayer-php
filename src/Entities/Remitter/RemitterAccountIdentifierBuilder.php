<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Remitter;

use TrueLayer\Entities\EntityBuilder;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Interfaces\Remitter\BbanRemitterInterface;
use TrueLayer\Interfaces\Remitter\IbanRemitterInterface;
use TrueLayer\Interfaces\Remitter\NrbRemitterInterface;
use TrueLayer\Interfaces\Remitter\RemitterAccountIdentifierBuilderInterface;
use TrueLayer\Interfaces\Remitter\ScanRemitterInterface;

class RemitterAccountIdentifierBuilder extends EntityBuilder implements RemitterAccountIdentifierBuilderInterface
{
    /**
     * @return IbanRemitterInterface
     * @throws InvalidArgumentException
     */
    public function iban(): IbanRemitterInterface
    {
        return $this->entityFactory->make(IbanRemitterInterface::class);
    }

    /**
     * @return BbanRemitterInterface
     * @throws InvalidArgumentException
     */
    public function bban(): BbanRemitterInterface
    {
        return $this->entityFactory->make(BbanRemitterInterface::class);
    }

    /**
     * @return ScanRemitterInterface
     * @throws InvalidArgumentException
     */
    public function sortCodeAccountNumber(): ScanRemitterInterface
    {
        return $this->entityFactory->make(ScanRemitterInterface::class);
    }

    /**
     * @return NrbRemitterInterface
     * @throws InvalidArgumentException
     */
    public function nrb(): NrbRemitterInterface
    {
        return $this->entityFactory->make(NrbRemitterInterface::class);
    }
}
