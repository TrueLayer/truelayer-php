<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Provider\SchemeSelection;

use TrueLayer\Entities\EntityBuilder;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Interfaces\Scheme\InstantOnlySchemeSelectionInterface;
use TrueLayer\Interfaces\Scheme\InstantPreferredSchemeSelectionInterface;
use TrueLayer\Interfaces\Scheme\PreselectedSchemeSelectionInterface;
use TrueLayer\Interfaces\Scheme\SchemeSelectionBuilderInterface;
use TrueLayer\Interfaces\Scheme\UserSelectedSchemeSelectionInterface;

class SchemeSelectionBuilder extends EntityBuilder implements SchemeSelectionBuilderInterface
{
    /**
     * @throws InvalidArgumentException
     *
     * @return UserSelectedSchemeSelectionInterface
     */
    public function userSelected(): UserSelectedSchemeSelectionInterface
    {
        return $this->entityFactory->make(UserSelectedSchemeSelectionInterface::class);
    }

    /**
     * @throws InvalidArgumentException
     *
     * @return InstantOnlySchemeSelectionInterface
     */
    public function instantOnly(): InstantOnlySchemeSelectionInterface
    {
        return $this->entityFactory->make(InstantOnlySchemeSelectionInterface::class);
    }

    /**
     * @throws InvalidArgumentException
     *
     * @return InstantPreferredSchemeSelectionInterface
     */
    public function instantPreferred(): InstantPreferredSchemeSelectionInterface
    {
        return $this->entityFactory->make(InstantPreferredSchemeSelectionInterface::class);
    }

    /**
     * @throws InvalidArgumentException
     *
     * @return PreselectedSchemeSelectionInterface
     */
    public function preselected(): PreselectedSchemeSelectionInterface
    {
        return $this->entityFactory->make(PreselectedSchemeSelectionInterface::class);
    }
}
