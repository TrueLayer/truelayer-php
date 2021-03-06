<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Provider\ProviderSelection;

use TrueLayer\Entities\EntityBuilder;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Exceptions\ValidationException;
use TrueLayer\Interfaces\Provider\ProviderSelectionBuilderInterface;
use TrueLayer\Interfaces\Provider\UserSelectedProviderSelectionInterface;

class ProviderSelectionBuilder extends EntityBuilder implements ProviderSelectionBuilderInterface
{
    /**
     * @throws InvalidArgumentException
     * @throws ValidationException
     *
     * @return UserSelectedProviderSelectionInterface
     */
    public function userSelected(): UserSelectedProviderSelectionInterface
    {
        return $this->entityFactory->make(UserSelectedProviderSelectionInterface::class);
    }
}
