<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces;

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
     * @return self
     */
    public function validate(): self;

    /**
     * @return mixed[]
     */
    public function errors(): array;
}
