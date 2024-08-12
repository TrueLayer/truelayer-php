<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Remitter\RemitterVerification;

use TrueLayer\Interfaces\ArrayableInterface;
use TrueLayer\Interfaces\HasAttributesInterface;

interface RemitterVerificationInterface extends ArrayableInterface, HasAttributesInterface
{
    /**
     * @return string
     */
    public function getType(): string;
}
