<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Provider\SchemeSelection;

use TrueLayer\Constants\SchemeSelectionTypes;
use TrueLayer\Entities\Entity;
use TrueLayer\Interfaces\Scheme\PreselectedSchemeSelectionInterface;

class PreselectedSchemeSelection extends Entity implements PreselectedSchemeSelectionInterface
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
        return SchemeSelectionTypes::PRESELECTED;
    }
}
