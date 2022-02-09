<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\AccountIdentifier;

interface NrbInterface extends NrbDetailsInterface
{
    /**
     * @param string $nrb
     *
     * @return NrbInterface
     */
    public function nrb(string $nrb): NrbInterface;
}
