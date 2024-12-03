<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payout\Scheme;

use TrueLayer\Constants\SchemeSelectionTypes;
use TrueLayer\Interfaces\Payout\Scheme\InstantOnlySchemeSelectionInterface;

class InstantOnlySchemeSelection extends SchemeSelection implements InstantOnlySchemeSelectionInterface
{
    /**
     * @return string
     */
    public function getType(): string
    {
        return SchemeSelectionTypes::INSTANT_ONLY;
    }
}
