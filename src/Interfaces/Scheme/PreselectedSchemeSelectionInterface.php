<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Scheme;

interface PreselectedSchemeSelectionInterface extends SchemeSelectionInterface
{
    /**
     * @return string|null
     */
    public function getSchemeId(): ?string;

    /**
     * @param string $schemeId
     * @return PreselectedSchemeSelectionInterface
     */
    public function schemeId(string $schemeId): PreselectedSchemeSelectionInterface;
}
