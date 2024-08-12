<?php

namespace TrueLayer\Interfaces\Remitter\RemitterVerification;

interface AutomatedRemitterVerificationInterface extends RemitterVerificationInterface
{
    /**
     * @return bool
     */
    public function getRemitterName(): bool;

    /**
     * @param bool $remitterName
     * @return RemitterVerificationInterface
     */
    public function remitterName(bool $remitterName): RemitterVerificationInterface;

    /**
     * @return bool
     */
    public function getRemitterDateOfBirth(): bool;

    /**
     * @param bool $remitterDateOfBirth
     * @return RemitterVerificationInterface
     */
    public function remitterDateOfBirth(bool $remitterDateOfBirth): RemitterVerificationInterface;
}
