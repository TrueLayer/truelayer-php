<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payout\Scheme;

use TrueLayer\Constants\SchemeSelectionTypes;
use TrueLayer\Interfaces\Payout\Scheme\InstantPreferredSchemeSelectionInterface;

class InstantPreferredSchemeSelection extends SchemeSelection implements InstantPreferredSchemeSelectionInterface
{
    /**
     * @return string
     */
    public function getType(): string
    {
        return SchemeSelectionTypes::INSTANT_PREFERRED;
    }
}
