<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payout\Scheme;

use TrueLayer\Entities\Entity;
use TrueLayer\Interfaces\Payout\Scheme\SchemeSelectionInterface;

abstract class SchemeSelection extends Entity implements SchemeSelectionInterface
{
    /**
     * @var string[]
     */
    protected array $arrayFields = [
        'type',
    ];

    /**
     * @return string
     */
    abstract public function getType(): string;
}
