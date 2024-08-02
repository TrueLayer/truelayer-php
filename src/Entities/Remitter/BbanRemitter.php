<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Remitter;

use TrueLayer\Constants\AccountIdentifierTypes;
use TrueLayer\Entities\Entity;
use TrueLayer\Interfaces\AccountIdentifier\BbanInterface;
use TrueLayer\Interfaces\Remitter\BbanRemitterInterface;

class BbanRemitter extends Entity implements BbanRemitterInterface
{
    /**
     * @var string
     */
    protected string $bban;

    /**
     * @var string[]
     */
    protected array $arrayFields = [
        'type',
        'bban',
    ];

    /**
     * @return string
     */
    public function getType(): string
    {
        return AccountIdentifierTypes::BBAN;
    }

    /**
     * @return string
     */
    public function getBban(): string
    {
        return $this->bban;
    }

    /**
     * @param string $bban
     * @return BbanInterface
     */
    public function bban(string $bban): BbanInterface
    {
        $this->bban = $bban;
        return $this;
    }
}
