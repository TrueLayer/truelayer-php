<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payout\Scheme;

use TrueLayer\Entities\EntityBuilder;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Interfaces\Payout\Scheme\InstantOnlySchemeSelectionInterface;
use TrueLayer\Interfaces\Payout\Scheme\InstantPreferredSchemeSelectionInterface;
use TrueLayer\Interfaces\Payout\Scheme\PreselectedSchemeSelectionInterface;
use TrueLayer\Interfaces\Payout\Scheme\SchemeSelectionBuilderInterface;

class SchemeSelectionBuilder extends EntityBuilder implements SchemeSelectionBuilderInterface
{
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
