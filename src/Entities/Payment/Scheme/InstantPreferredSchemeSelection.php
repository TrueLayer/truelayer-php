<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payment\Scheme;

use TrueLayer\Constants\SchemeSelectionTypes;
use TrueLayer\Interfaces\Payment\Scheme\InstantPreferredSchemeSelectionInterface;

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
