<?php

namespace TrueLayer\Contracts\Models;

interface BeneficiaryInterface
{
    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @return array
     */
    public function toArray(): array;
}
