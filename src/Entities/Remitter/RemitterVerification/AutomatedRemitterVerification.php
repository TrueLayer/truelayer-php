<?php

namespace TrueLayer\Entities\Remitter\RemitterVerification;

use TrueLayer\Constants\RemitterVerificationTypes;
use TrueLayer\Entities\Entity;
use TrueLayer\Interfaces\Remitter\RemitterVerification\AutomatedRemitterVerificationInterface;
use TrueLayer\Interfaces\Remitter\RemitterVerification\RemitterVerificationInterface;

final class AutomatedRemitterVerification extends Entity implements AutomatedRemitterVerificationInterface
{
    /**
     * @var bool
     */
    protected bool $remitterName;

    /**
     * @var bool
     */
    protected bool $remitterDateOfBirth;

    /**
     * @var string[]
     */
    protected array $arrayFields = [
        'remitter_name',
        'remitter_date_of_birth',
        'type',
    ];

    /**
     * @return bool
     */
    public function getRemitterName(): bool
    {
        $remitterName = $this->remitterName ?? null;

        return (bool) $remitterName;
    }

    /**
     * @param bool $remitterName
     * @return RemitterVerificationInterface
     */
    public function remitterName(bool $remitterName): RemitterVerificationInterface
    {
        $this->remitterName = $remitterName;
        return $this;
    }

    /**
     * @return bool
     */
    public function getRemitterDateOfBirth(): bool
    {
        $remitterDateOfBirth = $this->remitterDateOfBirth ?? null;

        return (bool) $remitterDateOfBirth;
    }

    /**
     * @param bool $remitterDateOfBirth
     * @return RemitterVerificationInterface
     */
    public function remitterDateOfBirth(bool $remitterDateOfBirth): RemitterVerificationInterface
    {
        $this->remitterDateOfBirth = $remitterDateOfBirth;
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return RemitterVerificationTypes::AUTOMATED;
    }
}
