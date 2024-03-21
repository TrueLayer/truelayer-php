<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces;

use TrueLayer\Exceptions\InvalidArgumentException;

interface HasAttributesInterface extends ArrayableInterface
{
    /**
     * @param mixed[] $data
     *
     * @throws InvalidArgumentException
     *
     * @return $this
     */
    public function fill(array $data): self;
}
