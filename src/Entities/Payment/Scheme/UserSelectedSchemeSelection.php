<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payment\Scheme;

use TrueLayer\Constants\SchemeSelectionTypes;
use TrueLayer\Entities\Entity;
use TrueLayer\Interfaces\Payment\Scheme\UserSelectedSchemeSelectionInterface;

class UserSelectedSchemeSelection extends Entity implements UserSelectedSchemeSelectionInterface
{
    /**
     * @var string[]
     */
    protected array $arrayFields = [
        'type',
    ];

    /**
     * @return string
     */
    public function getType(): string
    {
        return SchemeSelectionTypes::USER_SELECTED;
    }
}
