<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\SignupPlus;

use TrueLayer\Interfaces\HasAttributesInterface;

interface SignupPlusAuthUriCreatedInterface extends HasAttributesInterface
{
    /**
     * @return string
     */
    public function getAuthUri(): string;
}
