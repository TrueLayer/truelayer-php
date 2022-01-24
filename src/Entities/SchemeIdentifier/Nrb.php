<?php

declare(strict_types=1);

namespace TrueLayer\Entities\SchemeIdentifier;

use TrueLayer\Interfaces\SchemeIdentifier\NrbInterface;
use TrueLayer\Entities\Entity;

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
}
