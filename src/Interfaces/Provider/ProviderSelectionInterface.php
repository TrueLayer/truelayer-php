<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Provider;

use TrueLayer\Interfaces\ArrayableInterface;
use TrueLayer\Interfaces\HasAttributesInterface;

interface ProviderSelectionInterface extends ArrayableInterface, HasAttributesInterface
{
    /**
     * @return string
     */
    public function getType(): string;
}
