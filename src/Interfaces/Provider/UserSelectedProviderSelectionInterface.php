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
     *
     * @return UserSelectedProviderSelectionInterface
     */
    public function filter(ProviderFilter $filter): UserSelectedProviderSelectionInterface;
}
