<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Provider;

use TrueLayer\Interfaces\ArrayableInterface;
use TrueLayer\Interfaces\HasAttributesInterface;
use TrueLayer\Interfaces\Scheme\SchemeSelectionInterface;

interface ProviderSelectionInterface extends ArrayableInterface, HasAttributesInterface
{
    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @param SchemeSelectionInterface $schemeSelection
     *
     * @return ProviderSelectionInterface
     */
    public function schemeSelection(SchemeSelectionInterface $schemeSelection): ProviderSelectionInterface;

    /**
     * @return SchemeSelectionInterface|null
     */
    public function getSchemeSelection(): ?SchemeSelectionInterface;
}
