<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces;

use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Exceptions\ValidationException;

interface HasAttributesInterface extends ArrayableInterface
{
    /**
     * @param mixed[] $data
     *
     * @throws InvalidArgumentException
     * @throws ValidationException
     *
     * @return $this
     */
    public function fill(array $data): self;
}
