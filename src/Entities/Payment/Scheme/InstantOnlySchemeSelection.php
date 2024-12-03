<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payment\Scheme;

use TrueLayer\Constants\SchemeSelectionTypes;
use TrueLayer\Interfaces\Payment\Scheme\InstantOnlySchemeSelectionInterface;

class InstantOnlySchemeSelection extends InstantSchemeSelection implements InstantOnlySchemeSelectionInterface
{
    /**
     * @return string
     */
    public function getType(): string
    {
        return SchemeSelectionTypes::INSTANT_ONLY;
    }
}
