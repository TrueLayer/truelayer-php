<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Remitter\RemitterVerification;

interface RemitterVerificationBuilderInterface
{
    /**
     * @return AutomatedRemitterVerificationInterface
     * In an automated verification, Truelayer will perform additional checks on the remitter.
     * You can only perform additional checks for one of name or date of birth, not both.
     */
    public function automated(): AutomatedRemitterVerificationInterface;
}
