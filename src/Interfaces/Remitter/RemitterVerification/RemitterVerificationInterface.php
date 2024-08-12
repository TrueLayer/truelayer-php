<?php

namespace TrueLayer\Interfaces\Remitter\RemitterVerification;

interface RemitterVerificationInterface
{
    /**
     * @return string
     */
    public function getType(): string;
}
