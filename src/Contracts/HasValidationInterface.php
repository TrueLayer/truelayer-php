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
     * @throws ValidationException
     *
     * @return mixed[]
     */
    public function validate(): array;

    /**
     * @return mixed[]
     */
    public function errors(): array;
}
