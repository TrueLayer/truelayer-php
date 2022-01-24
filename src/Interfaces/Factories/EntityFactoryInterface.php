<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Factories;

use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Exceptions\ValidationException;

interface EntityFactoryInterface
{
    /**
     * @template T
     * @param class-string<T> $abstract
     * @return T
     * @throws InvalidArgumentException
     * @throws ValidationException
     */
    public function make(string $abstract);

    /**
     * @param string $abstract
     * @param array $data
     * @return array
     * @throws InvalidArgumentException
     * @throws ValidationException
     */
    public function makeMany(string $abstract, array $data): array;
}
