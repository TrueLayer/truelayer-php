<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payout\Scheme;

use TrueLayer\Constants\SchemeSelectionTypes;
use TrueLayer\Interfaces\Payout\Scheme\PreselectedSchemeSelectionInterface;

class PreselectedSchemeSelection extends SchemeSelection implements PreselectedSchemeSelectionInterface
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
