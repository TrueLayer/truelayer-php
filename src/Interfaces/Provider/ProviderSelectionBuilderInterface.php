<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Provider;

use TrueLayer\Interfaces\ArrayableInterface;
use TrueLayer\Interfaces\HasAttributesInterface;

interface ProviderSelectionBuilderInterface
{
    /**
     * @return UserSelectedProviderSelectionInterface
     */
    public function userSelected(): UserSelectedProviderSelectionInterface;
}
