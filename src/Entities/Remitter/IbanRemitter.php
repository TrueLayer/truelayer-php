<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Remitter;

use TrueLayer\Constants\AccountIdentifierTypes;
use TrueLayer\Entities\Entity;
use TrueLayer\Interfaces\Remitter\IbanRemitterInterface;

class IbanRemitter extends Entity implements IbanRemitterInterface
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
    public function getType(): string
    {
        return AccountIdentifierTypes::IBAN;
    }
}
