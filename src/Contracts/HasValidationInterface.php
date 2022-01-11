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
     * @return self
     */
    public function validate(): self;

    /**
     * @return mixed[]
     */
    public function errors(): array;
}
