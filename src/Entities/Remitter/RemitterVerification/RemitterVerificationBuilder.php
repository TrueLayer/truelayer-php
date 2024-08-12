<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Remitter\RemitterVerification;

use TrueLayer\Entities\EntityBuilder;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Interfaces\Remitter\RemitterVerification\AutomatedRemitterVerificationInterface;
use TrueLayer\Interfaces\Remitter\RemitterVerification\RemitterVerificationBuilderInterface;

class RemitterVerificationBuilder extends EntityBuilder implements RemitterVerificationBuilderInterface
{
    /**
     * @throws InvalidArgumentException
     *
     * @return AutomatedRemitterVerificationInterface
     */
    public function automated(): AutomatedRemitterVerificationInterface
    {
        return $this->entityFactory->make(AutomatedRemitterVerificationInterface::class);
    }
}
