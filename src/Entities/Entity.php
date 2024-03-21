<?php

declare(strict_types=1);

namespace TrueLayer\Entities;

use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Interfaces\ArrayableInterface;
use TrueLayer\Interfaces\Factories\EntityFactoryInterface;
use TrueLayer\Interfaces\HasAttributesInterface;
use TrueLayer\Traits\ArrayableAttributes;
use TrueLayer\Traits\CastsAttributes;

abstract class Entity implements ArrayableInterface, HasAttributesInterface
{
    use CastsAttributes;
    use ArrayableAttributes;

    /**
     * @var EntityFactoryInterface
     */
    protected EntityFactoryInterface $entityFactory;

    /**
     * @param EntityFactoryInterface $entityFactory
     */
    public function __construct(
        EntityFactoryInterface $entityFactory
    ) {
        $this->entityFactory = $entityFactory;
    }

    /**
     * @param mixed[] $data
     *
     * @throws InvalidArgumentException
     *
     * @return $this
     */
    public function fill(array $data): self
    {
        $data = $this->castData($data);
        $this->setValues($data);

        return $this;
    }

    /**
     * @template T
     *
     * @param class-string<T> $abstract
     * @param mixed[]|null    $data
     *
     * @throws InvalidArgumentException
     *
     * @return T
     */
    protected function make(string $abstract, array $data = null)
    {
        return $this->entityFactory->make($abstract, $data);
    }

    /**
     * @template T
     *
     * @param class-string<T> $abstract
     * @param mixed[]|null    $data
     *
     * @throws InvalidArgumentException
     *
     * @return T[]
     */
    protected function makeMany(string $abstract, array $data = null)
    {
        return $data ?
            $this->entityFactory->makeMany($abstract, $data)
            : [];
    }
}
