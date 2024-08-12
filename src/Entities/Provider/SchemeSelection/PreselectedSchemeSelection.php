<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Provider\SchemeSelection;

use TrueLayer\Constants\SchemeSelectionTypes;
use TrueLayer\Entities\Entity;
use TrueLayer\Interfaces\Scheme\PreselectedSchemeSelectionInterface;

class PreselectedSchemeSelection extends Entity implements PreselectedSchemeSelectionInterface
{
    protected string $schemeId;

    /**
     * @var string[]
     */
    protected array $arrayFields = [
        'type',
        'scheme_id',
    ];

    public function getSchemeId(): ?string
    {
        return $this->schemeId ?? null;
    }

    public function schemeId(string $schemeId): PreselectedSchemeSelectionInterface
    {
        $this->schemeId = $schemeId;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return SchemeSelectionTypes::PRESELECTED;
    }
}
