<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Scheme;

interface SchemeSelectionInterface
{
    /**
     * The type of scheme selection.
     *
     * @return string
     */
    public function getType(): string;
}
