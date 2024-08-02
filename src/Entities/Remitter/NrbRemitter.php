<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Remitter;

use TrueLayer\Constants\AccountIdentifierTypes;
use TrueLayer\Entities\Entity;
use TrueLayer\Interfaces\AccountIdentifier\NrbInterface;
use TrueLayer\Interfaces\Remitter\NrbRemitterInterface;

class NrbRemitter extends Entity implements NrbRemitterInterface
{
    /**
     * @var string
     */
    protected string $nrb;

    /**
     * @var string[]
     */
    protected array $arrayFields = [
        'type',
        'nrb',
    ];

    /**
     * @return string
     */
    public function getType(): string
    {
        return AccountIdentifierTypes::NRB;
    }

    /**
     * @return string
     */
    public function getNrb(): string
    {
        return $this->nrb;
    }

    /**
     * @param string $nrb
     * @return NrbInterface
     */
    public function nrb(string $nrb): NrbInterface
    {
        $this->nrb = $nrb;
        return $this;
    }
}
