<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Remitter\RemitterVerification;

interface RemitterVerificationInterface
{
    /**
     * @return string
     */
    public function getType(): string;
}
