<?php

declare(strict_types=1);

namespace TrueLayer\Contracts;

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
