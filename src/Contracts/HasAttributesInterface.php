<?php

declare(strict_types=1);

namespace TrueLayer\Contracts;

use TrueLayer\Exceptions\ValidationException;

interface HasAttributesInterface extends ArrayableInterface, HasValidationInterface
{
    /**
     * @param mixed[] $data
     *
     * @return $this
     */
    public function fill(array $data): self;
}
