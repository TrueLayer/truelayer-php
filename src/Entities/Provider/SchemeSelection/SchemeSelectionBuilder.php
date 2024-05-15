<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Provider\SchemeSelection;

use TrueLayer\Entities\EntityBuilder;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Interfaces\Scheme\InstantOnlySchemeSelectionInterface;
use TrueLayer\Interfaces\Scheme\InstantPreferredSchemeSelectionInterface;
use TrueLayer\Interfaces\Scheme\SchemeSelectionBuilderInterface;
use TrueLayer\Interfaces\Scheme\UserSelectedSchemeSelectionInterface;

class SchemeSelectionBuilder extends EntityBuilder implements SchemeSelectionBuilderInterface
{
    /**
     * @return UserSelectedSchemeSelectionInterface
     * @throws InvalidArgumentException
     */
    public function userSelected(): UserSelectedSchemeSelectionInterface
    {
        return $this->entityFactory->make(UserSelectedSchemeSelectionInterface::class);
    }

    /**
     * @return InstantOnlySchemeSelectionInterface
     * @throws InvalidArgumentException
     */
    public function instantOnly(): InstantOnlySchemeSelectionInterface
    {
        return $this->entityFactory->make(InstantOnlySchemeSelectionInterface::class);
    }

    /**
     * @return InstantPreferredSchemeSelectionInterface
     * @throws InvalidArgumentException
     */
    public function instantPreferred(): InstantPreferredSchemeSelectionInterface
    {
        return $this->entityFactory->make(InstantPreferredSchemeSelectionInterface::class);
    }
}
