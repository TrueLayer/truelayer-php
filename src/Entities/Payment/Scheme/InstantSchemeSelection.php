<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payment\Scheme;

use TrueLayer\Entities\Entity;
use TrueLayer\Interfaces\Payment\Scheme\InstantSchemeSelectionInterface;

abstract class InstantSchemeSelection extends Entity implements InstantSchemeSelectionInterface
{
    /**
     * @var string[]
     */
    protected array $arrayFields = [
        'type',
        'allow_remitter_fee',
    ];

    /**
     * @var bool
     */
    protected bool $allowRemitterFee = false;

    public function allowRemitterFee(bool $allow): InstantSchemeSelectionInterface
    {
        $this->allowRemitterFee = $allow;

        return $this;
    }

    /**
     * @return bool
     */
    public function getAllowRemitterFee(): bool
    {
        return $this->allowRemitterFee;
    }

    /**
     * @return string
     */
    abstract public function getType(): string;
}
