<?php

declare(strict_types=1);

namespace TrueLayer\Entities;

use TrueLayer\Interfaces\Factories\EntityFactoryInterface;

abstract class EntityBuilder
{
    /**
     * @var EntityFactoryInterface
     */
    protected EntityFactoryInterface $entityFactory;

    /**
     * @param EntityFactoryInterface $entityFactory
     */
    public function __construct(EntityFactoryInterface $entityFactory)
    {
        $this->entityFactory = $entityFactory;
    }
}
