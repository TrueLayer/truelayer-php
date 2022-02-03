<?php

declare(strict_types=1);

namespace TrueLayer\Entities\AccountIdentifier;

use TrueLayer\Constants\AccountIdentifierTypes;
use TrueLayer\Entities\Entity;
use TrueLayer\Interfaces\AccountIdentifier\NrbInterface;

final class Nrb extends Entity implements NrbInterface
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
     * @var string[]
     */
    protected array $rules = [
        'nrb' => 'required|numeric|digits:26',
    ];

    /**
     * @return string
     */
    public function getNrb(): string
    {
        return $this->nrb;
    }

    /**
     * @param string $nrb
     *
     * @return NrbInterface
     */
    public function nrb(string $nrb): NrbInterface
    {
        $this->nrb = $nrb;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return AccountIdentifierTypes::NRB;
    }
}
