<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Factories;

use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Exceptions\ValidationException;

interface EntityFactoryInterface
{
    /**
     * @template T
     *
     * @param class-string<T> $abstract
     * @param mixed[]|null    $data
     *
     * @throws InvalidArgumentException
     * @throws ValidationException
     *
     * @return T
     */
    public function make(string $abstract, array $data = null);

    /**
     * @template T
     *
     * @param class-string<T> $abstract
     * @param mixed[]         $data
     *
     * @throws InvalidArgumentException
     * @throws ValidationException
     *
     * @return T[]
     */
    public function makeMany(string $abstract, array $data): array;
}
