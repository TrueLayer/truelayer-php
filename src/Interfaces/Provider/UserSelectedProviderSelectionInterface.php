<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Provider;

use TrueLayer\Entities\Provider\ProviderSelection\ProviderFilter;

interface UserSelectedProviderSelectionInterface extends ProviderSelectionInterface
{
    /**
     * @return ProviderFilterInterface|null
     */
    public function getFilter(): ?ProviderFilterInterface;

    /**
     * @param ProviderFilter $filter
     * Configuration options to constrain which providers should be available
     * during the provider_selection action.
     *
     * @return UserSelectedProviderSelectionInterface
     *
     * @see TrueLayer\Interfaces\Client\ClientInterface::providerFilter()
     */
    public function filter(ProviderFilter $filter): UserSelectedProviderSelectionInterface;
}
