<?php

namespace TrueLayer\Contracts\Models;

use TrueLayer\Contracts\ArrayableInterface;
use TrueLayer\Contracts\ArrayFactoryInterface;

interface BeneficiaryInterface extends ArrayableInterface, ArrayFactoryInterface
{
    /**
     * @return string|null
     */
    public function getName(): ?string;

    /**
     * @param string $name
     *
     * @return $this
     */
    public function name(string $name): self;

    /**
     * @return string
     */
    public function getType(): string;
}
