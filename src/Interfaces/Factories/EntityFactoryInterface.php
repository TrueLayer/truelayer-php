<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Factories;

use TrueLayer\Exceptions\InvalidArgumentException;

interface EntityFactoryInterface
{
    /**
     * @template T
     *
     * @param class-string<T> $abstract
     * @param mixed[]|null $data
     *
     * @return T
     * @throws InvalidArgumentException
     */
    public function make(string $abstract, array $data = null);

    /**
     * @template T
     *
     * @param class-string<T> $concrete
     *
     * @return T
     * @throws InvalidArgumentException
     *
     */
    public function makeConcrete(string $concrete);

    /**
     * @template T
     *
     * @param class-string<T> $abstract
     * @param mixed[] $data
     *
     * @return T[]
     * @throws InvalidArgumentException
     */
    public function makeMany(string $abstract, array $data): array;
}
