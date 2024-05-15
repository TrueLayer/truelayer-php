<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Provider\SchemeSelection;

use TrueLayer\Constants\SchemeSelectionTypes;
use TrueLayer\Interfaces\Scheme\InstantPreferredSchemeSelectionInterface;

class InstantPreferredSchemeSelection extends InstantSchemeSelection implements InstantPreferredSchemeSelectionInterface
{
    /**
     * @return string
     */
    public function getType(): string
    {
        return SchemeSelectionTypes::INSTANT_PREFERRED;
    }
}
