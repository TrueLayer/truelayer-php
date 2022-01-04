<?php

declare(strict_types=1);

namespace TrueLayer\Contracts;

use TrueLayer\Exceptions\ValidationException;

interface HasValidationInterface extends ArrayableInterface
{
    /**
     * @return bool
     */
    public function isValid(): bool;

    /**
     * @return mixed[]
     * @throws ValidationException
     */
    public function validate(): array;

    /**
     * @return array
     */
    public function errors(): array;
}
