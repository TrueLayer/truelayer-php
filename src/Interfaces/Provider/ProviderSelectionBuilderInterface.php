<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Provider;

interface ProviderSelectionBuilderInterface
{
    /**
     * @return UserSelectedProviderSelectionInterface
     * Indicates that the provider is to be selected from a collection
     */
    public function userSelected(): UserSelectedProviderSelectionInterface;

    /**
     * @return PreselectedProviderSelectionInterface;
     * Preselected provider. Indicates that the provider for this payment is preselected.
     */
    public function preselected(): PreselectedProviderSelectionInterface;
}
