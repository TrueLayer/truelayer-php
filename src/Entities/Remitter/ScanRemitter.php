<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Remitter;

use TrueLayer\Constants\AccountIdentifierTypes;
use TrueLayer\Entities\Entity;
use TrueLayer\Interfaces\Remitter\ScanRemitterInterface;

class ScanRemitter extends Entity implements ScanRemitterInterface
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
        return AccountIdentifierTypes::SORT_CODE_ACCOUNT_NUMBER;
    }
}
