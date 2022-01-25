<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Provider;

use TrueLayer\Interfaces\ArrayableInterface;

interface ProviderInterface extends ArrayableInterface
{
    /**
     * @return string|null
     */
    public function getProviderId(): ?string;

    /**
     * @return string|null
     */
    public function getDisplayName(): ?string;

    /**
     * @return string|null
     */
    public function getIconUri(): ?string;

    /**
     * @return string|null
     */
    public function getLogoUri(): ?string;

    /**
     * @return string|null
     */
    public function getBgColour(): ?string;

    /**
     * @return string|null
     */
    public function getBgColor(): ?string;

    /**
     * @return string|null
     */
    public function getCountryCode(): ?string;
}
