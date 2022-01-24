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
     *
     * @throws InvalidArgumentException
     * @throws ValidationException
     *
     * @return T
     */
    public function make(string $abstract);

    /**
     * @param string $abstract
     * @param array  $data
     *
     * @throws InvalidArgumentException
     * @throws ValidationException
     *
     * @return array
     */
    public function makeMany(string $abstract, array $data): array;
}
