<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Provider;

interface ProviderSelectionBuilderInterface
{
    /**
     * @return UserSelectedProviderSelectionInterface
     */
    public function userSelected(): UserSelectedProviderSelectionInterface;
}
