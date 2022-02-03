<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\AccountIdentifier;

use TrueLayer\Interfaces\ArrayableInterface;

interface AccountIdentifierInterface extends ArrayableInterface
{
    /**
     * @return string
     */
    public function getType(): string;
}
