<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Remitter\RemitterVerification;

interface RemitterVerificationBuilderInterface
{
    /**
     * @return AutomatedRemitterVerificationInterface
     */
    public function automated(): AutomatedRemitterVerificationInterface;
}
