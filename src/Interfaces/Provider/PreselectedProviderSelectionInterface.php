<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Provider;

interface PreselectedProviderSelectionInterface extends ProviderSelectionInterface
{
    /**
     * @return string
     */
    public function getProviderId(): string;

    /**
     * @param string $providerId
     *
     * @return PreselectedProviderSelectionInterface
     */
    public function providerId(string $providerId): PreselectedProviderSelectionInterface;
}
