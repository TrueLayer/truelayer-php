<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Remitter\RemitterVerification;

interface AutomatedRemitterVerificationInterface extends RemitterVerificationInterface
{
    /**
     * @return bool
     */
    public function getRemitterName(): bool;

    /**
     * @param bool $remitterName
     * Enable verification for the remitter name.
     *
     * @return RemitterVerificationInterface
     */
    public function remitterName(bool $remitterName): RemitterVerificationInterface;

    /**
     * @return bool
     */
    public function getRemitterDateOfBirth(): bool;

    /**
     * @param bool $remitterDateOfBirth
     * Enable verification for the remitter date of birth.
     *
     * @return RemitterVerificationInterface
     */
    public function remitterDateOfBirth(bool $remitterDateOfBirth): RemitterVerificationInterface;
}
